<?php
namespace Flo\Configurator\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\App\ResourceConnection;

class Configurator extends Template
{
    protected $resource;

    public function __construct(
        Template\Context $context,
        ResourceConnection $resource,
        array $data = []
    ) {
        $this->resource = $resource;
        parent::__construct($context, $data);
    }

    public function getDeviceOptions()
    {
        $connection = $this->resource->getConnection();
        $optionTable = $this->resource->getTableName('flo_configurator_option');
        $mapTable = $this->resource->getTableName('flo_configurator_image_map');
        
        $select = $connection->select()
            ->from($optionTable)
            ->where('attribute_id = ?', 1)
            ->where('is_active = ?', 1)
            ->order('sort_order ASC');
            
        $options = $connection->fetchAll($select);
        
        $mediaUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $result = [];

        foreach ($options as $opt) {
            // Interogare mai permisivă pentru imagine
            $imgSelect = $connection->select()
                ->from($mapTable, ['image_path'])
                ->where('device_type_option_id = ?', $opt['option_id'])
                ->limit(1); // Luăm prima imagine găsită pentru acest device
            
            $imgPath = $connection->fetchOne($imgSelect);

            $result[] = [
                'id' => $opt['option_id'],
                'label' => $opt['label'],
                'image' => $imgPath ? $mediaUrl . 'configurator/' . ltrim($imgPath, '/') : false
            ];
        }

        return $result;
    }
}
