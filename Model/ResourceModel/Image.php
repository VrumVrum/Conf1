<?php
namespace Flo\Configurator\Model\ResourceModel;
class Image extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb {
    protected function _construct() {
        $this->_init('flo_configurator_image_map', 'image_id');
    }
}
