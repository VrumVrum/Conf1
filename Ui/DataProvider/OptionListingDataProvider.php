<?php
declare(strict_types=1);

namespace Flo\Configurator\Ui\DataProvider;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Flo\Configurator\Model\ResourceModel\Option\CollectionFactory;

class OptionListingDataProvider extends AbstractDataProvider
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
}
