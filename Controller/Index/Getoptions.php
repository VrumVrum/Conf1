<?php
namespace Flo\Configurator\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\ResultFactory;

class Getoptions extends Action
{
    public function execute()
    {
        $deviceId = $this->getRequest()->getParam('device_id');
        $collectionId = $this->getRequest()->getParam('collection_id');
        $colorId = $this->getRequest()->getParam('color_id');
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        
        try {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $resource = $objectManager->get(\Magento\Framework\App\ResourceConnection::class);
            $connection = $resource->getConnection();
            $tableName = $resource->getTableName('flo_configurator_option');
            $mediaUrl = "/media/configurator/";

            $totalPrice = 0.00;
            $devices = [];

            // Load Initial Devices if nothing is selected
            if (!$deviceId) {
                $selDevs = $connection->select()->from(['map' => $resource->getTableName('flo_configurator_image_map')], ['device_type_option_id', 'image_path'])->group('device_type_option_id');
                foreach ($connection->fetchAll($selDevs) as $row) {
                    $opt = $connection->fetchRow($connection->select()->from($tableName)->where('option_id = ?', (int)$row['device_type_option_id']));
                    if ($opt) $devices[] = ['option_id' => $opt['option_id'], 'label' => $opt['label'], 'image' => $mediaUrl . ltrim($row['image_path'], '/')];
                }
            }

            if ($deviceId) {
                $dPrice = $connection->fetchOne($connection->select()->from($tableName, 'price')->where('option_id = ?', (int)$deviceId));
                $totalPrice += (float)$dPrice;
            }

            if ($collectionId) {
                $cPrice = $connection->fetchOne($connection->select()->from($tableName, 'price')->where('option_id = ?', (int)$collectionId));
                $totalPrice += (float)$cPrice;
            }

            // Galerie Mapping - REPARAT ORDINEA
            $gallery = [];
            if ($deviceId) {
                $selectImg = $connection->select()->from(['map' => $resource->getTableName('flo_configurator_image_map')])
                    ->where('map.device_type_option_id = ?', $deviceId);
                
                if ($collectionId) {
                    $selectImg->where('map.collection_option_id = ?', $collectionId);
                }
                if ($colorId) {
                    $selectImg->where('map.color_option_id = ?', $colorId);
                }

                // Prioritizam rândurile care au cele mai multe potriviri (order by matches)
                $selectImg->order('map.color_option_id DESC')
                          ->order('map.collection_option_id DESC')
                          ->limit(1);

                $imageData = $connection->fetchRow($selectImg);
                
                if ($imageData) {
                    $fields = ['image_path', 'image_path_2', 'image_path_3', 'image_path_4', 'image_path_5'];
                    foreach ($fields as $f) {
                        if (!empty($imageData[$f])) {
                            $url = $mediaUrl . ltrim($imageData[$f], '/');
                            $gallery[] = [
                                'img' => $url, 
                                'full' => $url, 
                                'thumb' => $url, 
                                'isMain' => ($f == 'image_path'),
                                'caption' => 'Configurator Image'
                            ];
                        }
                    }
                }
            }

            // Colectii
            $collections = [];
            if ($deviceId) {
                $selCols = $connection->select()->from(['map' => $resource->getTableName('flo_configurator_image_map')], ['collection_option_id', 'image_path'])
                    ->where('map.device_type_option_id = ?', $deviceId)
                    ->group('collection_option_id');
                foreach ($connection->fetchAll($selCols) as $row) {
                    $opt = $connection->fetchRow($connection->select()->from($tableName)->where('option_id = ?', (int)$row['collection_option_id']));
                    if ($opt) $collections[] = ['option_id' => $opt['option_id'], 'label' => $opt['label'], 'image' => $mediaUrl . ltrim($row['image_path'], '/')];
                }
            }

            // Culori
            $colors = [];
            if ($deviceId && $collectionId) {
                $selColors = $connection->select()->from(['map' => $resource->getTableName('flo_configurator_image_map')], ['color_option_id', 'image_path'])
                    ->where('map.device_type_option_id = ?', $deviceId)
                    ->where('map.collection_option_id = ?', $collectionId);
                foreach ($connection->fetchAll($selColors) as $row) {
                    $opt = $connection->fetchRow($connection->select()->from($tableName)->where('option_id = ?', (int)$row['color_option_id']));
                    if ($opt) $colors[] = ['option_id' => $opt['option_id'], 'label' => $opt['label'], 'image' => $mediaUrl . ltrim($row['image_path'], '/')];
                }
            }

            return $resultJson->setData([
                'success' => true,
                'price' => number_format($totalPrice, 2, ',', '.'),
                'raw_price' => $totalPrice,
                'devices' => $devices,
                'gallery' => $gallery,
                'collections' => $collections,
                'colors' => $colors
            ]);
        } catch (\Exception $e) {
            return $resultJson->setData(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
