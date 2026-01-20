<?php
declare(strict_types=1);

namespace Flo\Configurator\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Flo\Configurator\Model\ResourceModel\Attribute\CollectionFactory;

class Attributes implements OptionSourceInterface
{
    private CollectionFactory $collectionFactory;

    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    public function toOptionArray(): array
    {
        $collection = $this->collectionFactory->create();
        $options = [];
        foreach ($collection as $attribute) {
            $options[] = [
                'value' => $attribute->getId(),
                'label' => $attribute->getLabel() . ' (ID: ' . $attribute->getId() . ')'
            ];
        }
        return $options;
    }
}
