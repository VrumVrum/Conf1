<?php
namespace Flo\Configurator\Controller\Adminhtml\Mageworxrule;

use Magento\Backend\App\Action;
use Flo\Configurator\Model\MageworxRuleFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends Action
{
    protected $dataPersistor;
    protected $ruleFactory;

    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        MageworxRuleFactory $ruleFactory
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->ruleFactory = $ruleFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            if (empty($data['rule_id'])) {
                unset($data['rule_id']);
            }

            $model = $this->ruleFactory->create();
            $id = $this->getRequest()->getParam('rule_id');
            if ($id) {
                $model->load($id);
            }

            $multiselects = ['device_ids', 'collection_ids', 'mageworx_option_ids'];
            foreach ($multiselects as $field) {
                if (isset($data[$field]) && is_array($data[$field])) {
                    $data[$field] = implode(',', $data[$field]);
                }
            }

            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the rule.'));
                $this->dataPersistor->clear('flo_configurator_mageworx_rule');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['rule_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/index');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the rule.'));
            }

            $this->dataPersistor->set('flo_configurator_mageworx_rule', $data);
            return $resultRedirect->setPath('*/*/edit', ['rule_id' => $this->getRequest()->getParam('rule_id')]);
        }
        return $resultRedirect->setPath('*/*/index');
    }
}
