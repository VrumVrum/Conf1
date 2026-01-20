<?php
declare(strict_types=1);

namespace Flo\Configurator\Model\ResourceModel\Image;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'image_id';

    protected function _construct()
    {
        $this->_init(
            \Flo\Configurator\Model\Image::class,
            \Flo\Configurator\Model\ResourceModel\Image::class
        );
    }
}
