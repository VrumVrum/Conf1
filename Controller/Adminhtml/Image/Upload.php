<?php
namespace Flo\Configurator\Controller\Adminhtml\Image;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Flo\Configurator\Model\ImageMap\ImageUploader;

class Upload extends Action
{
    protected $imageUploader;

    public function __construct(
        Context $context,
        ImageUploader $imageUploader
    ) {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }

    public function execute()
    {
        try {
            $fileId = '';
            $files = $this->getRequest()->getFiles();
            
            // Detectam automat care input a trimis fisierul
            if (count($files) > 0) {
                foreach ($files as $key => $value) {
                    $fileId = $key;
                    break;
                }
            }

            if (empty($fileId)) {
                $fileId = 'image_path';
            }

            $result = $this->imageUploader->saveFileToTmpDir($fileId);
            
            $result['cookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
