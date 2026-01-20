<?php
namespace Flo\Configurator\Model;

use Magento\Framework\App\Filesystem\DirectoryList;

class ImageUploader
{
    private $mediaDirectory;
    private $uploaderFactory;
    private $storeManager;
    private $baseTmpPath;

    public function __construct(
        \Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->uploaderFactory = $uploaderFactory;
        $this->storeManager = $storeManager;
        $this->baseTmpPath = 'configurator/tmp';
    }

    public function saveFileToTmpDir($fileId)
    {
        $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
        $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
        $uploader->setAllowRenameFiles(true);
        $result = $uploader->save($this->mediaDirectory->getAbsolutePath($this->baseTmpPath));

        // Generăm URL-ul absolut corect pentru preview
        $baseUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $result['url'] = $baseUrl . $this->baseTmpPath . '/' . $result['file'];
        $result['name'] = $result['file'];

        return $result;
    }
}
