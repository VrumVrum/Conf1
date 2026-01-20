<?php
/**
 * Flo_Configurator Attribute Repository Interface
 *
 * @category  Flo
 * @package   Flo_Configurator
 * @author    Your Company
 * @copyright Copyright (c) 2025
 */

declare(strict_types=1);

namespace Flo\Configurator\Api;

use Flo\Configurator\Api\Data\AttributeInterface;
use Magento\Framework\Exception\NoSuchEntityException;

interface AttributeRepositoryInterface
{
    /**
     * Get attribute by ID
     *
     * @param int $attributeId
     * @return AttributeInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $attributeId): AttributeInterface;

    /**
     * Get attribute by code
     *
     * @param string $attributeCode
     * @return AttributeInterface
     * @throws NoSuchEntityException
     */
    public function getByCode(string $attributeCode): AttributeInterface;

    /**
     * Get all active attributes ordered by sort_order
     *
     * @return AttributeInterface[]
     */
    public function getActiveAttributes(): array;

    /**
     * Save attribute
     *
     * @param AttributeInterface $attribute
     * @return AttributeInterface
     */
    public function save(AttributeInterface $attribute): AttributeInterface;

    /**
     * Delete attribute
     *
     * @param AttributeInterface $attribute
     * @return bool
     */
    public function delete(AttributeInterface $attribute): bool;
}