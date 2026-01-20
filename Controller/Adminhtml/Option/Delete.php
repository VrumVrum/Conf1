<?php
declare(strict_types=1);
namespace Flo\Configurator\Controller\Adminhtml\Option;
use Magento\Backend\App\Action;
use Flo\Configurator\Model\OptionFactory;
class Delete extends Action {
    protected $optionFactory;
    public function __construct(Action\Context $context, OptionFactory $optionFactory) {
        $this->optionFactory = $optionFactory;
        parent::__construct($context);
    }
    public function execute() {
        $id = $this->getRequest()->getParam('option_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->optionFactory->create();
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccessMessage(__('The option has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['option_id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a option to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
