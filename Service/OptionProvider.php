<?php
/**
 * Flo_Configurator Option Provider Service
 *
 * @category  Flo
 * @package   Flo_Configurator
 * @author    Your Company
 * @copyright Copyright (c) 2025
 */

declare(strict_types=1);

namespace Flo\Configurator\Service;

use Magento\Framework\App\ResourceConnection;
use Psr\Log\LoggerInterface;

class OptionProvider
{
    /**
     * @var ResourceConnection
     */
    private ResourceConnection $resource;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Constructor
     *
     * @param ResourceConnection $resource
     * @param LoggerInterface $logger
     */
    public function __construct(
        ResourceConnection $resource,
        LoggerInterface $logger
    ) {
        $this->resource = $resource;
        $this->logger = $logger;
    }

    /**
     * Get available options for attribute based on dependencies
     *
     * @param string $attributeCode
     * @param array $currentSelection
     * @return array
     */
    public function getAvailableOptions(string $attributeCode, array $currentSelection = []): array
    {
        try {
            $connection = $this->resource->getConnection();
            
            // Get attribute ID by code
            $attributeTable = $this->resource->getTableName('flo_configurator_attribute');
            $attributeId = $connection->fetchOne(
                $connection->select()
                    ->from($attributeTable, 'attribute_id')
                    ->where('attribute_code = ?', $attributeCode)
                    ->where('is_active = ?', 1)
            );

            if (!$attributeId) {
                return [];
            }

            // Get base options for this attribute
            $optionTable = $this->resource->getTableName('flo_configurator_option');
            $select = $connection->select()
                ->from($optionTable)
                ->where('attribute_id = ?', $attributeId)
                ->where('is_active = ?', 1)
                ->order('sort_order ASC');

            $allOptions = $connection->fetchAll($select);

            // If no dependencies exist for this attribute, return all options
            if (empty($currentSelection)) {
                return $this->formatOptions($allOptions);
            }

            // Filter by dependencies
            $filteredOptions = $this->filterByDependencies(
                $allOptions,
                $currentSelection,
                (int)$attributeId
            );

            return $this->formatOptions($filteredOptions);

        } catch (\Exception $e) {
            $this->logger->error('Failed to get available options', [
                'attribute_code' => $attributeCode,
                'current_selection' => $currentSelection,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Filter options by dependencies
     *
     * @param array $options
     * @param array $currentSelection
     * @param int $attributeId
     * @return array
     */
    private function filterByDependencies(array $options, array $currentSelection, int $attributeId): array
    {
        $connection = $this->resource->getConnection();
        $dependencyTable = $this->resource->getTableName('flo_configurator_option_dependency');

        // Get parent option IDs from current selection
        $parentOptionIds = array_filter(array_values($currentSelection), function ($value) {
            return is_numeric($value) && $value > 0;
        });

        if (empty($parentOptionIds)) {
            return $options;
        }

        // Get valid child option IDs based on dependencies
        $select = $connection->select()
            ->from($dependencyTable, 'child_option_id')
            ->where('parent_option_id IN (?)', $parentOptionIds);

        $validOptionIds = $connection->fetchCol($select);

        // If no dependencies found, check attribute-level dependencies
        if (empty($validOptionIds)) {
            $validOptionIds = $this->getAttributeLevelDependencies($parentOptionIds, $attributeId);
        }

        // If still no dependencies, return all options (no restrictions)
        if (empty($validOptionIds)) {
            return $options;
        }

        // Filter options to only valid ones
        return array_filter($options, function ($option) use ($validOptionIds) {
            return in_array($option['option_id'], $validOptionIds);
        });
    }

    /**
     * Get attribute-level dependencies
     *
     * @param array $parentOptionIds
     * @param int $childAttributeId
     * @return array
     */
    private function getAttributeLevelDependencies(array $parentOptionIds, int $childAttributeId): array
    {
        $connection = $this->resource->getConnection();
        $dependencyTable = $this->resource->getTableName('flo_configurator_dependency');
        $optionTable = $this->resource->getTableName('flo_configurator_option');

        // Check if child attribute is allowed for any parent options
        $select = $connection->select()
            ->from(['d' => $dependencyTable])
            ->join(
                ['o' => $optionTable],
                'd.child_attribute_id = o.attribute_id',
                ['option_id']
            )
            ->where('d.parent_option_id IN (?)', $parentOptionIds)
            ->where('d.child_attribute_id = ?', $childAttributeId)
            ->where('o.is_active = ?', 1);

        return $connection->fetchCol($select);
    }

    /**
     * Format options for API response
     *
     * @param array $options
     * @return array
     */
    private function formatOptions(array $options): array
    {
        return array_map(function ($option) {
            return [
                'option_id' => (int)$option['option_id'],
                'label' => $option['label'],
                'value' => $option['value'],
                'image' => $option['image'],
                'sort_order' => (int)$option['sort_order']
            ];
        }, $options);
    }
}