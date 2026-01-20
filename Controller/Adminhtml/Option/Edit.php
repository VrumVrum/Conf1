<?php
declare(strict_types=1);

namespace Flo\Configurator\Controller\Adminhtml\Option;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action
{
    protected $resultPageFactory;

    public function __construct(Context $context, PageFactory $resultPageFactory) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute() {
        $resultPage = $this->resultPageFactory->create();
        $id = $this->getRequest()->getParam('option_id');
        $resultPage->getConfig()->getTitle()->prepend($id ? __('Edit Option') : __('New Option'));
        return $resultPage;
    }
}
