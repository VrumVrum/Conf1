<?php
namespace Flo\Configurator\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Flo\Configurator\Model\Configurator\Source\Options;

class DeviceLabels extends Column {
    protected $options;
    public function __construct(
        ContextInterface $context, 
        UiComponentFactory $uiComponentFactory, 
        Options $options, 
        array $components = [], 
        array $data = []
    ) {
        $this->options = $options;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }
    public function prepareDataSource(array $dataSource) {
        if (isset($dataSource['data']['items'])) {
            $map = $this->options->getOptionMap();
            foreach ($dataSource['data']['items'] as &$item) {
                $fieldName = $this->getData('name');
                if (!empty($item[$fieldName])) {
                    $ids = is_array($item[$fieldName]) ? $item[$fieldName] : explode(',', (string)$item[$fieldName]);
                    $labels = [];
                    foreach ($ids as $id) { 
                        if (isset($map[$id])) $labels[] = $map[$id]; 
                    }
                    $item[$fieldName] = implode(', ', $labels);
                }
            }
        }
        return $dataSource;
    }
}
