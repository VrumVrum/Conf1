<?php
/**
 * Flo_Configurator Initial Data Patch
 *
 * @category  Flo
 * @package   Flo_Configurator
 * @author    Your Company
 * @copyright Copyright (c) 2025
 */

declare(strict_types=1);

namespace Flo\Configurator\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InitialConfiguratorData implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private ModuleDataSetupInterface $moduleDataSetup;

    /**
     * Constructor
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * @inheritDoc
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        // Insert Attributes
        $this->insertAttributes();
        
        // Insert Options
        $this->insertOptions();
        
        // Insert Price Rules
        $this->insertPriceRules();
        
        // Insert Dependencies
        $this->insertDependencies();

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * Insert configurator attributes
     *
     * @return void
     */
    private function insertAttributes(): void
    {
        $connection = $this->moduleDataSetup->getConnection();
        $tableName = $this->moduleDataSetup->getTable('flo_configurator_attribute');

        $attributes = [
            ['attribute_code' => 'device_type', 'label' => 'Device Type', 'sort_order' => 10, 'is_active' => 1],
            ['attribute_code' => 'collection', 'label' => 'Collection', 'sort_order' => 20, 'is_active' => 1],
            ['attribute_code' => 'color', 'label' => 'Color', 'sort_order' => 30, 'is_active' => 1],
            ['attribute_code' => 'size', 'label' => 'Size', 'sort_order' => 40, 'is_active' => 1],
            ['attribute_code' => 'extra_options', 'label' => 'Extra Options', 'sort_order' => 50, 'is_active' => 1],
        ];

        $connection->insertMultiple($tableName, $attributes);
    }

    /**
     * Insert configurator options
     *
     * @return void
     */
    private function insertOptions(): void
    {
        $connection = $this->moduleDataSetup->getConnection();
        $attributeTable = $this->moduleDataSetup->getTable('flo_configurator_attribute');
        $optionTable = $this->moduleDataSetup->getTable('flo_configurator_option');

        // Get attribute IDs
        $deviceTypeId = $connection->fetchOne(
            $connection->select()->from($attributeTable, 'attribute_id')->where('attribute_code = ?', 'device_type')
        );
        $collectionId = $connection->fetchOne(
            $connection->select()->from($attributeTable, 'attribute_id')->where('attribute_code = ?', 'collection')
        );
        $colorId = $connection->fetchOne(
            $connection->select()->from($attributeTable, 'attribute_id')->where('attribute_code = ?', 'color')
        );

        // Insert Device Types
        $deviceTypes = [
            ['attribute_id' => $deviceTypeId, 'label' => 'Phone Case', 'value' => 'phone', 'sort_order' => 10, 'is_active' => 1],
            ['attribute_id' => $deviceTypeId, 'label' => 'Tablet Case', 'value' => 'tablet', 'sort_order' => 20, 'is_active' => 1],
            ['attribute_id' => $deviceTypeId, 'label' => 'Laptop Case', 'value' => 'laptop', 'sort_order' => 30, 'is_active' => 1],
        ];
        $connection->insertMultiple($optionTable, $deviceTypes);

        // Insert Collections
        $collections = [
            ['attribute_id' => $collectionId, 'label' => 'FINN', 'value' => 'finn', 'sort_order' => 10, 'is_active' => 1],
            ['attribute_id' => $collectionId, 'label' => 'LEON', 'value' => 'leon', 'sort_order' => 20, 'is_active' => 1],
            ['attribute_id' => $collectionId, 'label' => 'VIGO', 'value' => 'vigo', 'sort_order' => 30, 'is_active' => 1],
        ];
        $connection->insertMultiple($optionTable, $collections);

        // Insert Colors
        $colors = [
            ['attribute_id' => $colorId, 'label' => 'Black', 'value' => 'black', 'sort_order' => 10, 'is_active' => 1],
            ['attribute_id' => $colorId, 'label' => 'Brown', 'value' => 'brown', 'sort_order' => 20, 'is_active' => 1],
            ['attribute_id' => $colorId, 'label' => 'Blue', 'value' => 'blue', 'sort_order' => 30, 'is_active' => 1],
        ];
        $connection->insertMultiple($optionTable, $colors);
    }

    /**
     * Insert price rules
     *
     * @return void
     */
    private function insertPriceRules(): void
    {
        $connection = $this->moduleDataSetup->getConnection();
        $optionTable = $this->moduleDataSetup->getTable('flo_configurator_option');
        $priceRuleTable = $this->moduleDataSetup->getTable('flo_configurator_price_rule');

        // Get option IDs
        $phoneId = $connection->fetchOne(
            $connection->select()->from($optionTable, 'option_id')->where('value = ?', 'phone')
        );
        $tabletId = $connection->fetchOne(
            $connection->select()->from($optionTable, 'option_id')->where('value = ?', 'tablet')
        );
        $laptopId = $connection->fetchOne(
            $connection->select()->from($optionTable, 'option_id')->where('value = ?', 'laptop')
        );
        $leonId = $connection->fetchOne(
            $connection->select()->from($optionTable, 'option_id')->where('value = ?', 'leon')
        );
        $vigoId = $connection->fetchOne(
            $connection->select()->from($optionTable, 'option_id')->where('value = ?', 'vigo')
        );

        // Base prices for device types
        $priceRules = [
            ['option_id' => $phoneId, 'price_type' => 'base', 'price_value' => 49.00, 'priority' => 0, 'is_active' => 1],
            ['option_id' => $tabletId, 'price_type' => 'base', 'price_value' => 69.00, 'priority' => 0, 'is_active' => 1],
            ['option_id' => $laptopId, 'price_type' => 'base', 'price_value' => 99.00, 'priority' => 0, 'is_active' => 1],
            
            // Collection modifiers
            ['option_id' => $leonId, 'price_type' => 'fixed', 'price_value' => 10.00, 'priority' => 10, 'is_active' => 1],
            ['option_id' => $vigoId, 'price_type' => 'percentage', 'price_value' => 15.00, 'priority' => 10, 'is_active' => 1],
        ];

        $connection->insertMultiple($priceRuleTable, $priceRules);
    }

    /**
     * Insert dependencies
     *
     * @return void
     */
    private function insertDependencies(): void
    {
        $connection = $this->moduleDataSetup->getConnection();
        $attributeTable = $this->moduleDataSetup->getTable('flo_configurator_attribute');
        $optionTable = $this->moduleDataSetup->getTable('flo_configurator_option');
        $dependencyTable = $this->moduleDataSetup->getTable('flo_configurator_dependency');

        // Get attribute IDs
        $deviceTypeAttrId = $connection->fetchOne(
            $connection->select()->from($attributeTable, 'attribute_id')->where('attribute_code = ?', 'device_type')
        );
        $collectionAttrId = $connection->fetchOne(
            $connection->select()->from($attributeTable, 'attribute_id')->where('attribute_code = ?', 'collection')
        );

        // Get all device type option IDs
        $deviceTypeOptions = $connection->fetchCol(
            $connection->select()->from($optionTable, 'option_id')->where('attribute_id = ?', $deviceTypeAttrId)
        );

        // All device types can access collection attribute
        $dependencies = [];
        foreach ($deviceTypeOptions as $optionId) {
            $dependencies[] = [
                'parent_attribute_id' => $deviceTypeAttrId,
                'parent_option_id' => $optionId,
                'child_attribute_id' => $collectionAttrId
            ];
        }

        $connection->insertMultiple($dependencyTable, $dependencies);
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases(): array
    {
        return [];
    }
}