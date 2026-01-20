<?php
/**
 * Flo_Configurator Attribute Data Interface
 *
 * @category  Flo
 * @package   Flo_Configurator
 * @author    Your Company
 * @copyright Copyright (c) 2025
 */

declare(strict_types=1);

namespace Flo\Configurator\Api\Data;

interface AttributeInterface
{
    public const ATTRIBUTE_ID = 'attribute_id';
    public const ATTRIBUTE_CODE = 'attribute_code';
    public const LABEL = 'label';
    public const SORT_ORDER = 'sort_order';
    public const IS_ACTIVE = 'is_active';

    /**
     * Get attribute ID
     *
     * @return int|null
     */
    public function getAttributeId(): ?int;

    /**
     * Set attribute ID
     *
     * @param int $attributeId
     * @return $this
     */
    public function setAttributeId(int $attributeId): self;

    /**
     * Get attribute code
     *
     * @return string
     */
    public function getAttributeCode(): string;

    /**
     * Set attribute code
     *
     * @param string $code
     * @return $this
     */
    public function setAttributeCode(string $code): self;

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * Set label
     *
     * @param string $label
     * @return $this
     */
    public function setLabel(string $label): self;

    /**
     * Get sort order
     *
     * @return int
     */
    public function getSortOrder(): int;

    /**
     * Set sort order
     *
     * @param int $sortOrder
     * @return $this
     */
    public function setSortOrder(int $sortOrder): self;

    /**
     * Get is active
     *
     * @return bool
     */
    public function getIsActive(): bool;

    /**
     * Set is active
     *
     * @param bool $isActive
     * @return $this
     */
    public function setIsActive(bool $isActive): self;
}