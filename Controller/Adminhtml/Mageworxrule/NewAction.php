<?php
namespace Flo\Configurator\Controller\Adminhtml\Mageworxrule;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\ForwardFactory;

class NewAction extends Action
{
    protected $resultForwardFactory;

    public function __construct(
        Action\Context $context,
        ForwardFactory $resultForwardFactory
    ) {
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        return $this->resultForwardFactory->create()->forward('edit');
    }
}
