<?php
/**
 * Flo_Configurator Attribute Collection
 *
 * @category  Flo
 * @package   Flo_Configurator
 * @author    Your Company
 * @copyright Copyright (c) 2025
 */

declare(strict_types=1);

namespace Flo\Configurator\Model\ResourceModel\Attribute;

use Flo\Configurator\Model\Attribute as AttributeModel;
use Flo\Configurator\Model\ResourceModel\Attribute as AttributeResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'attribute_id';

    /**
     * Initialize collection model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(AttributeModel::class, AttributeResource::class);
    }

    /**
     * Filter by active status
     *
     * @return $this
     */
    public function addActiveFilter(): self
    {
        return $this->addFieldToFilter('is_active', 1);
    }

    /**
     * Set order by sort_order
     *
     * @return $this
     */
    public function setOrderBySortOrder(): self
    {
        return $this->setOrder('sort_order', 'ASC');
    }
}