<?php
namespace Flo\Configurator\Model\ResourceModel\Option;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
class Collection extends AbstractCollection {
    protected $_idFieldName = 'option_id';
    protected function _construct() {
        $this->_init(
            \Flo\Configurator\Model\Option::class,
            \Flo\Configurator\Model\ResourceModel\Option::class
        );
    }
}
