<?php
namespace Flo\Configurator\Model\ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
class Option extends AbstractDb {
    protected function _construct() {
        // Numele tabelului cu optiuni (device, collection, color)
        $this->_init('flo_configurator_option', 'option_id');
    }
}
