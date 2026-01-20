<?php
namespace Flo\Configurator\Controller\Adminhtml\Mageworxrule;

use Magento\Backend\App\Action;
use Flo\Configurator\Model\MageworxRuleFactory;

class Delete extends Action
{
    protected $ruleFactory;

    public function __construct(Action\Context $context, MageworxRuleFactory $ruleFactory) {
        $this->ruleFactory = $ruleFactory;
        parent::__construct($context);
    }

    public function execute() {
        $id = $this->getRequest()->getParam('rule_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->ruleFactory->create();
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccessMessage(__('Rule deleted successfully.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }
        return $resultRedirect->setPath('*/*/');
    }
}
