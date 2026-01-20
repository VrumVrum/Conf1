<?php
/**
 * Flo_Configurator Attribute Resource Model
 *
 * @category  Flo
 * @package   Flo_Configurator
 * @author    Your Company
 * @copyright Copyright (c) 2025
 */

declare(strict_types=1);

namespace Flo\Configurator\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Attribute extends AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init('flo_configurator_attribute', 'attribute_id');
    }

    /**
     * Load attribute by code
     *
     * @param \Flo\Configurator\Model\Attribute $attribute
     * @param string $code
     * @return $this
     */
    public function loadByCode(\Flo\Configurator\Model\Attribute $attribute, string $code): self
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable())
            ->where('attribute_code = ?', $code);

        $data = $connection->fetchRow($select);
        if ($data) {
            $attribute->setData($data);
        }

        return $this;
    }
}