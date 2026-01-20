<?php
namespace Flo\Configurator\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Flo\Configurator\Model\ResourceModel\MageworxRule\CollectionFactory;
use Magento\Framework\Serialize\Serializer\Json;

class MageworxRules implements ArgumentInterface
{
    protected $collectionFactory;
    protected $serializer;

    public function __construct(
        CollectionFactory $collectionFactory,
        Json $serializer
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->serializer = $serializer;
    }

    public function getRulesJson()
    {
        $collection = $this->collectionFactory->create();
        $rules = [];
        foreach ($collection as $rule) {
            $rules[] = [
                'devices' => explode(',', (string)$rule->getDeviceIds()),
                'collections' => explode(',', (string)$rule->getCollectionIds()),
                'mageworx_id' => $rule->getMageworxOptionIds()
            ];
        }
        return $this->serializer->serialize($rules);
    }
}
