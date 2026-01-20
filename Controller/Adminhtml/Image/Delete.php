<?php
namespace Flo\Configurator\Controller\Adminhtml\Image;

use Magento\Backend\App\Action;

class Delete extends Action
{
    protected $imageFactory;

    public function __construct(
        Action\Context $context,
        \Flo\Configurator\Model\ImageFactory $imageFactory
    ) {
        parent::__construct($context);
        $this->imageFactory = $imageFactory;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('image_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->imageFactory->create();
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccessMessage(__('Mapping deleted successfully.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }
        return $resultRedirect->setPath('*/*/');
    }
}
