<?php
namespace Flo\Configurator\Controller\Adminhtml\Image;

use Magento\Backend\App\Action;
use Flo\Configurator\Model\ImageMapFactory;
use Flo\Configurator\Model\ImageMap\ImageUploader;
use Magento\Framework\App\Request\DataPersistorInterface;

class Save extends Action
{
    protected $dataPersistor;
    protected $imageMapFactory;
    protected $imageUploader;

    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        ImageMapFactory $imageMapFactory,
        ImageUploader $imageUploader
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->imageMapFactory = $imageMapFactory;
        $this->imageUploader = $imageUploader;
        parent::__construct($context);
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            // Cele 5 campuri de imagini
            $imageFields = ['image_path', 'image_path_2', 'image_path_3', 'image_path_4', 'image_path_5'];
            
            foreach ($imageFields as $field) {
                if (isset($data[$field]) && is_array($data[$field])) {
                    if (isset($data[$field][0]['name'])) {
                        // Numele fisierului
                        $imageName = $data[$field][0]['name'];
                        
                        // LOGICA: Daca avem informatii de tmp (url sau tmp_name), inseamna ca e UPLOAD NOU
                        // Si trebuie mutat. Daca e doar name, e poza veche.
                        if (isset($data[$field][0]['tmp_name']) || (isset($data[$field][0]['url']) && strpos($data[$field][0]['url'], '/tmp/') !== false)) {
                            $data[$field] = $this->imageUploader->moveFileFromTmp($imageName);
                        } else {
                            $data[$field] = $imageName;
                        }
                    } else {
                        $data[$field] = null;
                    }
                } else {
                    // Daca nu e setat sau e gol, inseamna ca s-a sters poza
                    // UI Component trimite array gol cand stergi
                    if (empty($data[$field])) {
                        $data[$field] = null;
                    }
                }
            }

            // Clean dropdowns
            $dropdownFields = ['device_type_option_id', 'collection_option_id', 'color_option_id'];
            foreach ($dropdownFields as $dd) {
                if (isset($data[$dd]) && $data[$dd] === '') {
                    $data[$dd] = null;
                }
            }

            $id = $this->getRequest()->getParam('image_id');
            $model = $this->imageMapFactory->create();

            if ($id) {
                $model->load($id);
                if (!$model->getId()) {
                    $this->messageManager->addErrorMessage(__('This image map no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the image map.'));
                $this->dataPersistor->clear('flo_configurator_image');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['image_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, $e->getMessage());
                $this->dataPersistor->set('flo_configurator_image', $data);
                return $resultRedirect->setPath('*/*/edit', ['image_id' => $this->getRequest()->getParam('image_id')]);
            }
        }
        return $resultRedirect->setPath('*/*/');
    }
}
