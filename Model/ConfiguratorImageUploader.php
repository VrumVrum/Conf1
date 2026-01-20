<?php
namespace Flo\Configurator\Model;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Store\Model\StoreManagerInterface;

class ConfiguratorImageUploader
{
    private $mediaDirectory;
    private $storeManager;
    protected $baseTmpPath = 'configurator/tmp';
    protected $basePath = 'configurator';

    public function __construct(
        Filesystem $filesystem,
        StoreManagerInterface $storeManager
    ) {
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->storeManager = $storeManager;
    }

    public function saveFileToTmp($fileId)
    {
        try {
            if (!isset($_FILES[$fileId])) throw new \Exception("Fisier lipsa.");
            $file = $_FILES[$fileId];
            
            // Metoda bruta de extragere nume si extensie
            $originalName = (string)$file['name'];
            $ext = strtolower(substr(strrchr($originalName, '.'), 1));
            $nameWithoutExt = str_replace('.'.$ext, '', $originalName);
            $cleanName = preg_replace('/[^a-zA-Z0-9_-]/', '', $nameWithoutExt);
            $fileName = $cleanName . '.' . $ext;
            
            $realMediaRoot = realpath(BP) . '/pub/media/';
            $absolutePath = $realMediaRoot . $this->baseTmpPath;
            if (!is_dir($absolutePath)) mkdir($absolutePath, 0777, true);

            $destination = $absolutePath . '/' . $fileName;
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                chmod($destination, 0666);
                
                $baseUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
                $fileUrl = rtrim($baseUrl, '/') . '/' . $this->baseTmpPath . '/' . $fileName;
                
                // Fortam un raspuns JSON pe care JS-ul nu il poate interpreta gresit
                return [
                    'file' => $fileName,
                    'url'  => $fileUrl,
                    'name' => $fileName,
                    'type' => 'image/jpeg',
                    'size' => (string)filesize($destination),
                    'previewType' => 'image'
                ];
            }
        } catch (\Exception $e) { return ['error' => $e->getMessage()]; }
        return ['error' => 'Upload failed'];
    }

    public function moveFileFromTmp($imageName)
    {
        $imageName = basename($imageName);
        $realMediaRoot = realpath(BP) . '/pub/media/';
        $source = $realMediaRoot . $this->baseTmpPath . '/' . $imageName;
        $dest = $realMediaRoot . $this->basePath . '/' . $imageName;

        if (file_exists($source)) {
            if (!is_dir(dirname($dest))) mkdir(dirname($dest), 0777, true);
            rename($source, $dest);
            chmod($dest, 0664);
        }
        return $imageName;
    }
}
