<?php
namespace Flo\Configurator\Model\ImageMap;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\File\Uploader;

class ImageUploader
{
    protected $coreFileStorageDatabase;
    protected $filesystem;
    protected $uploaderFactory;
    protected $storeManager;
    protected $logger;
    public $baseTmpPath;
    public $basePath;
    public $allowedExtensions;

    public function __construct(
        \Magento\MediaStorage\Helper\File\Storage\Database $coreFileStorageDatabase,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->coreFileStorageDatabase = $coreFileStorageDatabase;
        $this->filesystem = $filesystem;
        $this->uploaderFactory = $uploaderFactory;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
        
        $this->baseTmpPath = 'configurator/tmp';
        $this->basePath = 'configurator';
        $this->allowedExtensions = ['jpg', 'jpeg', 'gif', 'png'];
    }

    public function saveFileToTmpDir($fileId)
    {
        // 1. Cale explicita PUB/MEDIA
        $rootPath = $this->filesystem->getDirectoryRead(DirectoryList::ROOT)->getAbsolutePath();
        $mediaPath = rtrim($rootPath, '/') . '/pub/media';
        
        // Folosim $this->baseTmpPath corect
        $targetPath = $mediaPath . '/' . $this->baseTmpPath;

        if (!is_dir($targetPath)) {
            mkdir($targetPath, 0777, true);
        }

        $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
        $uploader->setAllowedExtensions($this->allowedExtensions);
        $uploader->setAllowRenameFiles(true);
        $uploader->setFilesDispersion(false);

        // 2. Curatare Nume
        $rawName = $_FILES[$fileId]['name'];
        $pathInfo = pathinfo($rawName);
        $extension = $pathInfo['extension'];
        $onlyName = $pathInfo['filename'];
        $cleanName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $onlyName);
        $finalFileName = $cleanName . '.' . $extension;

        // 3. Salvare
        $result = $uploader->save($targetPath, $finalFileName);

        if (!$result) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('File can not be saved to the destination folder.')
            );
        }

        // 4. URL (Aici era eroarea, am pus $this->baseTmpPath)
        $result['tmp_name'] = str_replace('\\', '/', $result['tmp_name']);
        $result['path'] = str_replace('\\', '/', $result['path']);
        
        $result['url'] = $this->storeManager->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $this->getFilePath($this->baseTmpPath, $result['file']);
        
        $result['name'] = $result['file'];

        return $result;
    }

    public function moveFileFromTmp($imageName)
    {
        $rootPath = $this->filesystem->getDirectoryRead(DirectoryList::ROOT)->getAbsolutePath();
        $mediaPath = rtrim($rootPath, '/') . '/pub/media';

        $source = $mediaPath . '/' . $this->getFilePath($this->baseTmpPath, $imageName);
        $destination = $mediaPath . '/' . $this->getFilePath($this->basePath, $imageName);
        $destDir = dirname($destination);

        try {
            if (!is_dir($destDir)) {
                mkdir($destDir, 0777, true);
            }

            if (file_exists($source)) {
                rename($source, $destination);
            }
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }

        return $imageName;
    }

    public function getFilePath($path, $imageName)
    {
        return rtrim($path, '/') . '/' . ltrim($imageName, '/');
    }
}
