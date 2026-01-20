<?php
declare(strict_types=1);

namespace Flo\Configurator\Block\Product;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;

class Configurator extends Template
{
    protected $registry;

    public function __construct(
        Template\Context $context,
        Registry $registry,
        array $data = []
    ) {
        $this->registry = $registry;
        parent::__construct($context, $data);
    }

    public function getProduct()
    {
        return $this->registry->registry('product');
    }

    public function isConfiguratorEnabled(): bool
    {
        $product = $this->getProduct();
        return $product && (bool)$product->getData('enable_configurator');
    }

    protected function _toHtml()
    {
        if (!$this->isConfiguratorEnabled()) {
            return '';
        }
        return parent::_toHtml();
    }

    public function getProductId(): ?int
    {
        $product = $this->getProduct();
        return $product ? (int)$product->getId() : null;
    }
}
