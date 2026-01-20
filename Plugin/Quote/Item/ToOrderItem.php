<?php
/**
 * Flo_Configurator Quote Item to Order Item Plugin
 *
 * @category  Flo
 * @package   Flo_Configurator
 * @author    Your Company
 * @copyright Copyright (c) 2025
 */

declare(strict_types=1);

namespace Flo\Configurator\Plugin\Quote\Item;

use Magento\Quote\Model\Quote\Item\AbstractItem;
use Magento\Quote\Model\Quote\Item\ToOrderItem as QuoteToOrderItem;
use Magento\Sales\Api\Data\OrderItemInterface;

class ToOrderItem
{
    /**
     * Transfer configurator data from quote item to order item
     *
     * @param QuoteToOrderItem $subject
     * @param OrderItemInterface $orderItem
     * @param AbstractItem $quoteItem
     * @param array $data
     * @return OrderItemInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterConvert(
        QuoteToOrderItem $subject,
        OrderItemInterface $orderItem,
        AbstractItem $quoteItem,
        array $data = []
    ): OrderItemInterface {
        // Get configurator option from quote item
        $configuratorOption = $quoteItem->getOptionByCode('configurator_data');
        
        if ($configuratorOption) {
            // Create product option for order item
            $options = $orderItem->getProductOptions() ?: [];
            $options['configurator_data'] = $configuratorOption->getValue();
            
            $orderItem->setProductOptions($options);
        }

        return $orderItem;
    }
}