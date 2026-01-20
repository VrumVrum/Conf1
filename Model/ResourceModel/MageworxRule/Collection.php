<?php
namespace Flo\Configurator\Model\ResourceModel\MageworxRule;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'rule_id';

    protected function _construct()
    {
        $this->_init(
            \Flo\Configurator\Model\MageworxRule::class,
            \Flo\Configurator\Model\ResourceModel\MageworxRule::class
        );
    }
}
