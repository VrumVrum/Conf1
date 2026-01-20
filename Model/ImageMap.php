<?php
namespace Flo\Configurator\Model;

use Magento\Framework\Model\AbstractModel;

class ImageMap extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Flo\Configurator\Model\ResourceModel\ImageMap::class);
    }
}
