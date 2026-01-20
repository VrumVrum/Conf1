<?php
namespace Flo\Configurator\Block\Adminhtml\MageworxRule\Edit;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
class BackButton implements ButtonProviderInterface {
    public function getButtonData() {
        return [
            'label' => __('Back'),
            'on_click' => sprintf("location.href = '%s';", 'index'),
            'class' => 'back',
            'sort_order' => 10
        ];
    }
}
