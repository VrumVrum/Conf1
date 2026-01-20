<?php
namespace Flo\Configurator\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ImageMap extends AbstractDb
{
    protected function _construct()
    {
        // ATENTIE: Aici trebuie sa fie numele exact al tabelului din DB
        $this->_init('flo_configurator_image_map', 'image_id');
    }
}
