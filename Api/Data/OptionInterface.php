<?php
/**
 * Flo_Configurator Option Data Interface
 *
 * @category  Flo
 * @package   Flo_Configurator
 * @author    Your Company
 * @copyright Copyright (c) 2025
 */

declare(strict_types=1);

namespace Flo\Configurator\Api\Data;

interface OptionInterface
{
    public const OPTION_ID = 'option_id';
    public const ATTRIBUTE_ID = 'attribute_id';
    public const LABEL = 'label';
    public const VALUE = 'value';
    public const IMAGE = 'image';
    public const SORT_ORDER = 'sort_order';
    public const IS_ACTIVE = 'is_active';

    /**
     * Get option ID
     *
     * @return int|null
     */
    public function getOptionId(): ?int;

    /**
     * Set option ID
     *
     * @param int $optionId
     * @return $this
     */
    public function setOptionId(int $optionId): self;

    /**
     * Get attribute ID
     *
     * @return int
     */
    public function getAttributeId(): int;

    /**
     * Set attribute ID
     *
     * @param int $attributeId
     * @return $this
     */
    public function setAttributeId(int $attributeId): self;

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
     * Get value
     *
     * @return string
     */
    public function getValue(): string;

    /**
     * Set value
     *
     * @param string $value
     * @return $this
     */
    public function setValue(string $value): self;

    /**
     * Get image
     *
     * @return string|null
     */
    public function getImage(): ?string;

    /**
     * Set image
     *
     * @param string|null $image
     * @return $this
     */
    public function setImage(?string $image): self;

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