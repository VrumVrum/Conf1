<?php
namespace Flo\Configurator\Model;

use Magento\Framework\Model\AbstractModel;

class MageworxRule extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Flo\Configurator\Model\ResourceModel\MageworxRule::class);
    }
}
