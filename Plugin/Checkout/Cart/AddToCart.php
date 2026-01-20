<?php
/**
 * Flo_Configurator Add to Cart Plugin
 *
 * @category  Flo
 * @package   Flo_Configurator
 * @author    Your Company
 * @copyright Copyright (c) 2025
 */

declare(strict_types=1);

namespace Flo\Configurator\Plugin\Checkout\Cart;

use Flo\Configurator\Service\PriceCalculator;
use Magento\Checkout\Model\Cart;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Psr\Log\LoggerInterface;

class AddToCart
{
    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var PriceCalculator
     */
    private PriceCalculator $priceCalculator;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Constructor
     *
     * @param RequestInterface $request
     * @param PriceCalculator $priceCalculator
     * @param SerializerInterface $serializer
     * @param LoggerInterface $logger
     */
    public function __construct(
        RequestInterface $request,
        PriceCalculator $priceCalculator,
        SerializerInterface $serializer,
        LoggerInterface $logger
    ) {
        $this->request = $request;
        $this->priceCalculator = $priceCalculator;
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    /**
     * After addProduct plugin
     *
     * @param Cart $subject
     * @param mixed $result
     * @return mixed
     */
    public function afterAddProduct(Cart $subject, $result)
    {
        try {
            $configuratorData = $this->request->getParam('configurator_data');
            
            if (!$configuratorData) {
                return $result;
            }

            // Decode configuration
            $configuration = $this->serializer->unserialize($configuratorData);
            
            if (empty($configuration)) {
                return $result;
            }

            // Get the quote item that was just added
            $quote = $subject->getQuote();
            $items = $quote->getAllItems();
            $quoteItem = end($items); // Get last added item

            if (!$quoteItem) {
                return $result;
            }

            // Calculate custom price
            $productId = (int)$quoteItem->getProductId();
            $mageWorxPrice = (float)$this->request->getParam('mageworx_price', 0.0);
            
            $finalPrice = $this->priceCalculator->calculatePrice(
                $productId,
                $configuration,
                $mageWorxPrice
            );

            // Set custom price
            $quoteItem->setCustomPrice($finalPrice);
            $quoteItem->setOriginalCustomPrice($finalPrice);
            $quoteItem->getProduct()->setIsSuperMode(true);

            // Store configuration in quote item option
            $quoteItem->addOption([
                'code' => 'configurator_data',
                'value' => $configuratorData,
                'product_id' => $productId
            ]);

            // Save quote
            $quote->save();

        } catch (\Exception $e) {
            $this->logger->error('Failed to process configurator add to cart', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return $result;
    }
}