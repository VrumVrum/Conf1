<?php
namespace Flo\Configurator\Block\Adminhtml\ImageMap\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    public function getButtonData()
    {
        $id = $this->getImageId();
        if (!$id) {
            return [];
        }
        return [
            'label' => __('Delete'),
            'class' => 'delete',
            'on_click' => 'deleteConfirm(\'' . __('Are you sure you want to do this?') . '\', \'' . $this->getDeleteUrl() . '\')',
            'sort_order' => 20,
        ];
    }

    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', ['image_id' => $this->getImageId()]);
    }
}
