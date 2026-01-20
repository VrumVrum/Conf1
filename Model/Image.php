<?php
namespace Flo\Configurator\Model;
class Image extends \Magento\Framework\Model\AbstractModel {
    protected function _construct() {
        $this->_init(\Flo\Configurator\Model\ResourceModel\Image::class);
    }
}
