<?php
namespace Flo\Configurator\Block\Adminhtml\Option\Edit;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
class BackButton implements ButtonProviderInterface {
    public function getButtonData() {
        return ['label' => __('Back'), 'class' => 'back', 'sort_order' => 10, 'on_click' => "location.href = '#'"];
    }
}
