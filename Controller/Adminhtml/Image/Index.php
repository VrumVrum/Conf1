<?php
declare(strict_types=1);

namespace Flo\Configurator\Controller\Adminhtml\Image;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    protected $resultPageFactory;

    public function __construct(Context $context, PageFactory $resultPageFactory) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute() {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Configurator Images'));
        return $resultPage;
    }
}
