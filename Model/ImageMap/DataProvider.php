<?php
namespace Flo\Configurator\Model\ImageMap;

use Flo\Configurator\Model\ResourceModel\ImageMap\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Store\Model\StoreManagerInterface;

class DataProvider extends AbstractDataProvider
{
    protected $collection;
    protected $loadedData;
    protected $storeManager;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        $baseUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        foreach ($items as $item) {
            $data = $item->getData();
            $imageFields = ['image_path', 'image_path_2', 'image_path_3', 'image_path_4', 'image_path_5'];
            
            foreach ($imageFields as $field) {
                if (!empty($data[$field])) {
                    $imgName = $data[$field];
                    $data[$field] = [[
                        'name' => $imgName,
                        'url'  => $baseUrl . 'configurator/' . $imgName,
                        'size' => 0,
                        'type' => 'image',
                        'previewType' => 'image'
                    ]];
                }
            }
            $this->loadedData[$item->getId()] = $data;
        }
        return $this->loadedData;
    }
}
