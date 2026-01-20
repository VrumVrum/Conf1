<?php
declare(strict_types=1);

namespace Flo\Configurator\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class ConfiguratorData implements ArgumentInterface
{
    public function __construct() {}

    public function getAttributesJson(): string
    {
        return '[]';
    }
}
