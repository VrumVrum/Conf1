<?php
namespace Flo\Configurator\Controller\Adminhtml\Image;

use Magento\Backend\App\Action;

class Edit extends Action
{
    protected $resultPageFactory;

    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Edit Image Mapping'));
        return $resultPage;
    }
}
