<?php
namespace Flo\Configurator\Model\Configurator\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Flo\Configurator\Model\ResourceModel\Option\CollectionFactory;

class ColorOptions implements OptionSourceInterface
{
    protected $collectionFactory;

    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    public function toOptionArray()
    {
        $collection = $this->collectionFactory->create();
        // Filtram dupa attribute_id = 3 (Presupunem ca 3 sunt Culorile)
        $collection->addFieldToFilter('attribute_id', 3);
        $collection->addFieldToFilter('is_active', 1);
        $collection->setOrder('label', 'ASC');

        $options = [['value' => '', 'label' => __('-- Please Select --')]];
        foreach ($collection as $item) {
            $options[] = [
                'value' => $item->getId(),
                'label' => $item->getLabel()
            ];
        }
        return $options;
    }
}
