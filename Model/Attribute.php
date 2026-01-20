<?php
/**
 * Flo_Configurator Attribute Model
 *
 * @category  Flo
 * @package   Flo_Configurator
 * @author    Your Company
 * @copyright Copyright (c) 2025
 */

declare(strict_types=1);

namespace Flo\Configurator\Model;

use Flo\Configurator\Api\Data\AttributeInterface;
use Magento\Framework\Model\AbstractModel;

class Attribute extends AbstractModel implements AttributeInterface
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(\Flo\Configurator\Model\ResourceModel\Attribute::class);
    }

    /**
     * @inheritDoc
     */
    public function getAttributeId(): ?int
    {
        $value = $this->getData(self::ATTRIBUTE_ID);
        return $value !== null ? (int)$value : null;
    }

    /**
     * @inheritDoc
     */
    public function setAttributeId(int $attributeId): AttributeInterface
    {
        return $this->setData(self::ATTRIBUTE_ID, $attributeId);
    }

    /**
     * @inheritDoc
     */
    public function getAttributeCode(): string
    {
        return (string)$this->getData(self::ATTRIBUTE_CODE);
    }

    /**
     * @inheritDoc
     */
    public function setAttributeCode(string $code): AttributeInterface
    {
        return $this->setData(self::ATTRIBUTE_CODE, $code);
    }

    /**
     * @inheritDoc
     */
    public function getLabel(): string
    {
        return (string)$this->getData(self::LABEL);
    }

    /**
     * @inheritDoc
     */
    public function setLabel(string $label): AttributeInterface
    {
        return $this->setData(self::LABEL, $label);
    }

    /**
     * @inheritDoc
     */
    public function getSortOrder(): int
    {
        return (int)$this->getData(self::SORT_ORDER);
    }

    /**
     * @inheritDoc
     */
    public function setSortOrder(int $sortOrder): AttributeInterface
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    /**
     * @inheritDoc
     */
    public function getIsActive(): bool
    {
        return (bool)$this->getData(self::IS_ACTIVE);
    }

    /**
     * @inheritDoc
     */
    public function setIsActive(bool $isActive): AttributeInterface
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }
}