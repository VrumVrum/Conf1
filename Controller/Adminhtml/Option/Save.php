<?php
namespace Flo\Configurator\Controller\Adminhtml\Option;

use Magento\Backend\App\Action;
use Flo\Configurator\Model\OptionFactory;

class Save extends Action
{
    protected $optionFactory;

    public function __construct(
        Action\Context $context,
        OptionFactory $optionFactory
    ) {
        $this->optionFactory = $optionFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();
        
        if ($data) {
            $model = $this->optionFactory->create();
            $id = $this->getRequest()->getParam('option_id');
            
            if ($id) {
                $model->load($id);
            }

            // Mapare date pe coloanele reale din DB-ul tau
            $model->setData('attribute_id', $data['attribute_id'] ?? null);
            $model->setData('label', $data['label'] ?? '');
            // Mapam codul intern pe coloana 'value' conform capturii tale
            $model->setData('value', $data['internal_value_code'] ?? ($data['value'] ?? ''));
            $model->setData('sort_order', $data['sort_order'] ?? 0);
            $model->setData('is_active', $data['is_active'] ?? 1);
            $model->setData('price', $data['price'] ?? 0);

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('Option saved successfully.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['option_id' => $id]);
            }
        }
        return $resultRedirect->setPath('*/*/');
    }
}
