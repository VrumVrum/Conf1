<?php
declare(strict_types=1);

namespace Flo\Configurator\Ui\DataProvider;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Flo\Configurator\Model\ResourceModel\Image\CollectionFactory;

class ImageListingDataProvider extends AbstractDataProvider
{
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }

    public function getData()
    {
        $data = $this->getCollection()->toArray();
        return [
            'totalRecords' => $this->getCollection()->getSize(),
            'items' => array_values($data['items'] ?? [])
        ];
    }
}
