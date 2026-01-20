<?php
/**
 * Flo_Configurator Price Calculator Service
 *
 * @category  Flo
 * @package   Flo_Configurator
 * @author    Your Company
 * @copyright Copyright (c) 2025
 */

declare(strict_types=1);

namespace Flo\Configurator\Service;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Psr\Log\LoggerInterface;

class PriceCalculator
{
    private const PRICE_TYPE_BASE = 'base';
    private const PRICE_TYPE_FIXED = 'fixed';
    private const PRICE_TYPE_PERCENTAGE = 'percentage';

    /**
     * @var ResourceConnection
     */
    private ResourceConnection $resource;

    /**
     * @var PriceCurrencyInterface
     */
    private PriceCurrencyInterface $priceCurrency;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Constructor
     *
     * @param ResourceConnection $resource
     * @param PriceCurrencyInterface $priceCurrency
     * @param LoggerInterface $logger
     */
    public function __construct(
        ResourceConnection $resource,
        PriceCurrencyInterface $priceCurrency,
        LoggerInterface $logger
    ) {
        $this->resource = $resource;
        $this->priceCurrency = $priceCurrency;
        $this->logger = $logger;
    }

    /**
     * Calculate final price based on configuration
     *
     * @param int $productId
     * @param array $configuration
     * @param float $mageWorxOptionsPrice
     * @return float
     */
    public function calculatePrice(
        int $productId,
        array $configuration,
        float $mageWorxOptionsPrice = 0.0
    ): float {
        try {
            $basePrice = 0.0;
            $modifiers = [];

            // Get all price rules for selected options
            $priceRules = $this->getPriceRules($configuration);

            // Step 1: Get base price from device type
            foreach ($priceRules as $rule) {
                if ($rule['price_type'] === self::PRICE_TYPE_BASE) {
                    $basePrice = (float)$rule['price_value'];
                    break; // Only one base price
                }
            }

            if ($basePrice === 0.0) {
                $this->logger->warning('No base price found for configuration', [
                    'product_id' => $productId,
                    'configuration' => $configuration
                ]);
                return 0.0;
            }

            // Step 2: Collect all modifiers (fixed and percentage)
            foreach ($priceRules as $rule) {
                if ($rule['price_type'] !== self::PRICE_TYPE_BASE) {
                    $modifiers[] = $rule;
                }
            }

            // Sort modifiers by priority
            usort($modifiers, function ($a, $b) {
                return $a['priority'] <=> $b['priority'];
            });

            // Step 3: Apply modifiers in order
            $currentPrice = $basePrice;
            
            foreach ($modifiers as $modifier) {
                if ($modifier['price_type'] === self::PRICE_TYPE_FIXED) {
                    $currentPrice += (float)$modifier['price_value'];
                } elseif ($modifier['price_type'] === self::PRICE_TYPE_PERCENTAGE) {
                    $percentage = (float)$modifier['price_value'];
                    $currentPrice += ($currentPrice * $percentage / 100);
                }
            }

            // Step 4: Add MageWorx options price
            $currentPrice += $mageWorxOptionsPrice;

            // Round to 2 decimals
            return round($currentPrice, 2);

        } catch (\Exception $e) {
            $this->logger->error('Price calculation failed', [
                'product_id' => $productId,
                'configuration' => $configuration,
                'error' => $e->getMessage()
            ]);
            return 0.0;
        }
    }

    /**
     * Get price rules for selected options
     *
     * @param array $configuration
     * @return array
     */
    private function getPriceRules(array $configuration): array
    {
        $optionIds = array_filter(array_values($configuration), function ($value) {
            return is_numeric($value) && $value > 0;
        });

        if (empty($optionIds)) {
            return [];
        }

        $connection = $this->resource->getConnection();
        $tableName = $this->resource->getTableName('flo_configurator_price_rule');

        $select = $connection->select()
            ->from($tableName)
            ->where('option_id IN (?)', $optionIds)
            ->where('is_active = ?', 1)
            ->order('priority ASC');

        return $connection->fetchAll($select);
    }

    /**
     * Format price for display
     *
     * @param float $price
     * @return string
     */
    public function formatPrice(float $price): string
    {
        return $this->priceCurrency->format($price, false);
    }
}