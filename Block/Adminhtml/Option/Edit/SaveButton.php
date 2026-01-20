<?php
namespace Flo\Configurator\Block\Adminhtml\Option\Edit;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
class SaveButton implements ButtonProviderInterface {
    public function getButtonData() {
        return ['label' => __('Save'), 'class' => 'save primary', 'sort_order' => 90];
    }
}
