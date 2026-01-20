<?php
/**
 * Flo_Configurator Options Controller
 *
 * @category  Flo
 * @package   Flo_Configurator
 * @author    Your Company
 * @copyright Copyright (c) 2025
 */

declare(strict_types=1);

namespace Flo\Configurator\Controller\Options;

use Flo\Configurator\Service\OptionProvider;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Psr\Log\LoggerInterface;

class Index implements HttpGetActionInterface
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
     * @var OptionProvider
     */
    private OptionProvider $optionProvider;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Constructor
     *
     * @param JsonFactory $resultJsonFactory
     * @param RequestInterface $request
     * @param OptionProvider $optionProvider
     * @param LoggerInterface $logger
     */
    public function __construct(
        JsonFactory $resultJsonFactory,
        RequestInterface $request,
        OptionProvider $optionProvider,
        LoggerInterface $logger
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->request = $request;
        $this->optionProvider = $optionProvider;
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
            $attributeCode = $this->request->getParam('attribute');
            $currentSelection = $this->request->getParam('selection', []);

            if (!$attributeCode) {
                return $result->setData([
                    'success' => false,
                    'message' => __('Attribute code is required')
                ])->setHttpResponseCode(400);
            }

            // Decode JSON if selection is string
            if (is_string($currentSelection)) {
                $currentSelection = json_decode($currentSelection, true) ?? [];
            }

            $options = $this->optionProvider->getAvailableOptions(
                $attributeCode,
                $currentSelection
            );

            return $result->setData([
                'success' => true,
                'options' => $options
            ]);

        } catch (\Exception $e) {
            $this->logger->error('Failed to get options', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $result->setData([
                'success' => false,
                'message' => __('An error occurred while loading options')
            ])->setHttpResponseCode(500);
        }
    }
}