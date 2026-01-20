<?php
namespace Flo\Configurator\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Store\Model\StoreManagerInterface;

class Thumbnail extends \Magento\Ui\Component\Listing\Columns\Column
{
    protected $storeManager;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        StoreManagerInterface $storeManager,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->storeManager = $storeManager;
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $path = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'configurator/';
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['image_path'])) {
                    $item['image_path_src'] = $path . $item['image_path'];
                    $item['image_path_orig_src'] = $path . $item['image_path'];
                    $item['image_path_link'] = $this->context->getUrl('flo_configurator/image/edit', ['image_id' => $item['image_id']]);
                    $item['image_path_alt'] = $item['image_path'];
                }
            }
        }
        return $dataSource;
    }
}
