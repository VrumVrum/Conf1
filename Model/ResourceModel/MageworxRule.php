<?php
namespace Flo\Configurator\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class MageworxRule extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('flo_configurator_mageworx_rule', 'rule_id');
    }
}
