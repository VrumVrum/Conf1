<?php
declare(strict_types=1);

namespace Flo\Configurator\Model\Source;

class Options implements \Magento\Framework\Data\OptionSourceInterface
{
    protected $collectionFactory;
    protected $attributeCode;

    public function __construct(
        \Flo\Configurator\Model\ResourceModel\Option\CollectionFactory $collectionFactory,
        $attributeCode = null
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->attributeCode = $attributeCode;
    }

    public function toOptionArray()
    {
        $options = [['value' => '', 'label' => __('-- None --')]];
        $collection = $this->collectionFactory->create();
        if ($this->attributeCode) {
            $collection->getSelect()->join(
                ['attr' => $collection->getTable('flo_configurator_attribute')],
                'main_table.attribute_id = attr.attribute_id',
                []
            )->where('attr.attribute_code = ?', $this->attributeCode);
        }
        foreach ($collection as $option) {
            $options[] = ['value' => $option->getId(), 'label' => $option->getLabel()];
        }
        return $options;
    }
}
