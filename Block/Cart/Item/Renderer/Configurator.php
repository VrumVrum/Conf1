<?php
/**
 * Flo_Configurator Cart Item Renderer
 *
 * @category  Flo
 * @package   Flo_Configurator
 * @author    Your Company
 * @copyright Copyright (c) 2025
 */

declare(strict_types=1);

namespace Flo\Configurator\Block\Cart\Item\Renderer;

use Magento\Checkout\Block\Cart\Item\Renderer;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Helper\Product\Configuration;
use Magento\Checkout\Model\Session;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Url\Helper\Data as UrlHelper;
use Magento\Msrp\Helper\Data as MsrpHelper;

class Configurator extends Renderer
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * Constructor
     *
     * @param Context $context
     * @param Configuration $productConfig
     * @param Session $checkoutSession
     * @param Image $imageHelper
     * @param UrlHelper $urlHelper
     * @param ManagerInterface $messageManager
     * @param PriceCurrencyInterface $priceCurrency
     * @param SerializerInterface $serializer
     * @param MsrpHelper $msrpHelper
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        Context $context,
        Configuration $productConfig,
        Session $checkoutSession,
        Image $imageHelper,
        UrlHelper $urlHelper,
        ManagerInterface $messageManager,
        PriceCurrencyInterface $priceCurrency,
        SerializerInterface $serializer,
        MsrpHelper $msrpHelper,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $productConfig,
            $checkoutSession,
            $imageHelper,
            $urlHelper,
            $messageManager,
            $priceCurrency,
            $msrpHelper,
            $data
        );
        $this->serializer = $serializer;
    }

    /**
     * Get configurator data from item
     *
     * @return array
     */
    public function getConfiguratorData(): array
    {
        $item = $this->getItem();
        $option = $item->getOptionByCode('configurator_data');
        
        if (!$option) {
            return [];
        }

        try {
            return $this->serializer->unserialize($option->getValue());
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Check if item has configurator data
     *
     * @return bool
     */
    public function hasConfiguratorData(): bool
    {
        return !empty($this->getConfiguratorData());
    }

    /**
     * Get formatted configurator options for display
     *
     * @return array
     */
    public function getFormattedConfiguratorOptions(): array
    {
        $data = $this->getConfiguratorData();
        $formatted = [];

        foreach ($data as $key => $value) {
            $formatted[] = [
                'label' => ucwords(str_replace('_', ' ', $key)),
                'value' => $value
            ];
        }

        return $formatted;
    }
}