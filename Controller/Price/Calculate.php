<?php
/**
 * Flo_Configurator Price Controller
 *
 * @category  Flo
 * @package   Flo_Configurator
 * @author    Your Company
 * @copyright Copyright (c) 2025
 */

declare(strict_types=1);

namespace Flo\Configurator\Controller\Price;

use Flo\Configurator\Service\PriceCalculator;
use Flo\Configurator\Service\ImageResolver;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Psr\Log\LoggerInterface;

class Calculate implements HttpPostActionInterface
{
    /**
     * @var JsonFactory
     */
    private JsonFactory $resultJsonFactory;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var PriceCalculator
     */
    private PriceCalculator $priceCalculator;

    /**
     * @var ImageResolver
     */
    private ImageResolver $imageResolver;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Constructor
     *
     * @param JsonFactory $resultJsonFactory
     * @param RequestInterface $request
     * @param PriceCalculator $priceCalculator
     * @param ImageResolver $imageResolver
     * @param LoggerInterface $logger
     */
    public function __construct(
        JsonFactory $resultJsonFactory,
        RequestInterface $request,
        PriceCalculator $priceCalculator,
        ImageResolver $imageResolver,
        LoggerInterface $logger
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->request = $request;
        $this->priceCalculator = $priceCalculator;
        $this->imageResolver = $imageResolver;
        $this->logger = $logger;
    }

    /**
     * Execute action
     *
     * @return Json
     */
    public function execute(): Json
    {
        $result = $this->resultJsonFactory->create();

        try {
            $productId = (int)$this->request->getParam('product_id');
            $configuration = $this->request->getParam('configuration', []);
            $mageWorxOptionsPrice = (float)$this->request->getParam('mageworx_price', 0.0);

            if (!$productId) {
                return $result->setData([
                    'success' => false,
                    'message' => __('Product ID is required')
                ])->setHttpResponseCode(400);
            }

            // Decode JSON if configuration is string
            if (is_string($configuration)) {
                $configuration = json_decode($configuration, true) ?? [];
            }

            // Calculate price
            $finalPrice = $this->priceCalculator->calculatePrice(
                $productId,
                $configuration,
                $mageWorxOptionsPrice
            );

            // Resolve image
            $imageUrl = $this->imageResolver->resolveImage($configuration);

            return $result->setData([
                'success' => true,
                'price' => $finalPrice,
                'formatted_price' => $this->priceCalculator->formatPrice($finalPrice),
                'image_url' => $imageUrl
            ]);

        } catch (\Exception $e) {
            $this->logger->error('Failed to calculate price', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $result->setData([
                'success' => false,
                'message' => __('An error occurred while calculating price')
            ])->setHttpResponseCode(500);
        }
    }
}