<?php
declare(strict_types=1);
namespace Flo\Configurator\Block\Adminhtml\Option\Edit;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Backend\Block\Widget\Context;
class DeleteButton implements ButtonProviderInterface {
    protected $context;
    public function __construct(Context $context) { $this->context = $context; }
    public function getButtonData() {
        $id = $this->context->getRequest()->getParam('option_id');
        if ($id) {
            return [
                'label' => __('Delete Option'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' . __('Are you sure you want to delete this?') . '\', \'' . $this->context->getUrlBuilder()->getUrl('*/*/delete', ['option_id' => $id]) . '\')',
                'sort_order' => 20,
            ];
        }
        return [];
    }
}
