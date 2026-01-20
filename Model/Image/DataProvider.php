<?php
namespace Flo\Configurator\Model\Image;

use Flo\Configurator\Model\ResourceModel\ImageMap\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Store\Model\StoreManagerInterface;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $collection;
    protected $dataPersistor;
    protected $loadedData;
    protected $storeManager;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        $mediaBaseUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $mediaUrl = rtrim($mediaBaseUrl, '/') . '/configurator/';

        foreach ($items as $model) {
            $data = $model->getData();
            $imageFields = ['image_path', 'image_path_2', 'image_path_3', 'image_path_4', 'image_path_5'];
            
            foreach ($imageFields as $field) {
                if (isset($data[$field]) && $data[$field] && !is_array($data[$field])) {
                    $imgName = ltrim($data[$field], '/');
                    $data[$field] = [
                        [
                            'name' => $imgName,
                            'url' => $mediaUrl . $imgName
                        ]
                    ];
                }
            }
            $this->loadedData[$model->getId()] = $data;
        }
        return $this->loadedData;
    }
}
