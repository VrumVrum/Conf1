<?php
namespace Flo\Configurator\Model\Configurator\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Flo\Configurator\Model\ResourceModel\Option\CollectionFactory;

class DeviceOptions implements OptionSourceInterface
{
    protected $collectionFactory;

    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    public function toOptionArray()
    {
        $collection = $this->collectionFactory->create();
        // Filtram dupa attribute_id = 1 (Device Types)
        $collection->addFieldToFilter('attribute_id', 1);
        $collection->addFieldToFilter('is_active', 1); // Luam doar cele active
        $collection->setOrder('label', 'ASC');

        $options = [['value' => '', 'label' => __('-- Please Select --')]];
        foreach ($collection as $item) {
            $options[] = [
                'value' => $item->getId(),
                // Atentie: coloana din DB este 'label', deci folosim getLabel()
                'label' => $item->getLabel()
            ];
        }
        return $options;
    }
}
