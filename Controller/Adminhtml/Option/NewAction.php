<?php
declare(strict_types=1);

namespace Flo\Configurator\Controller\Adminhtml\Option;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\ForwardFactory;

class NewAction extends Action
{
    protected $resultForwardFactory;

    public function __construct(Context $context, ForwardFactory $resultForwardFactory) {
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);
    }

    public function execute() {
        return $this->resultForwardFactory->create()->forward('edit');
    }
}
