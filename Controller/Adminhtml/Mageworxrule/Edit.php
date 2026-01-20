<?php
namespace Flo\Configurator\Controller\Adminhtml\Mageworxrule;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action
{
    protected $resultPageFactory;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Flo_Configurator::mageworx_rules');
        $resultPage->getConfig()->getTitle()->prepend(__('Mageworx Rule'));
        return $resultPage;
    }
}