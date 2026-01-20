<?php
namespace Flo\Configurator\Model\MageworxRule;

use Flo\Configurator\Model\ResourceModel\MageworxRule\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    protected $loadedData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        foreach ($items as $rule) {
            $ruleData = $rule->getData();
            
            // Device si Collection raman multiselect (array)
            $multiselects = ['device_ids', 'collection_ids'];
            foreach ($multiselects as $field) {
                if (isset($ruleData[$field]) && !is_array($ruleData[$field])) {
                    $ruleData[$field] = explode(',', (string)$ruleData[$field]);
                }
            }
            
            // Mageworx ramane valoare simpla (string/int) pentru dropdown
            // Daca in DB e salvat cu virgula din greseala, luam prima valoare
            if (isset($ruleData['mageworx_option_ids']) && strpos($ruleData['mageworx_option_ids'], ',') !== false) {
                $parts = explode(',', $ruleData['mageworx_option_ids']);
                $ruleData['mageworx_option_ids'] = reset($parts);
            }
            
            $this->loadedData[$rule->getId()] = $ruleData;
        }

        return $this->loadedData;
    }
}
