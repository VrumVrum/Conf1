<?php
namespace Flo\Configurator\Model\ResourceModel\ImageMap;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'image_id';

    protected function _construct()
    {
        $this->_init(
            \Flo\Configurator\Model\ImageMap::class,
            \Flo\Configurator\Model\ResourceModel\ImageMap::class
        );
    }
}
