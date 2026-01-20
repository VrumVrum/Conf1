<?php
namespace Flo\Configurator\Block\Adminhtml\ImageMap\Edit;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Exception\NoSuchEntityException;

class GenericButton
{
    protected $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }

    public function getImageId()
    {
        return $this->context->getRequest()->getParam('image_id');
    }
}
