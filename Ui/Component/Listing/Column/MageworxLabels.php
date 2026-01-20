<?php
namespace Flo\Configurator\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Flo\Configurator\Model\Configurator\Source\MageworxTemplates;

class MageworxLabels extends Column {
    protected $source;
    public function __construct(ContextInterface $context, UiComponentFactory $uiComponentFactory, MageworxTemplates $source, array $components = [], array $data = []) {
        $this->source = $source;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }
    public function prepareDataSource(array $dataSource) {
        if (isset($dataSource['data']['items'])) {
            $options = $this->source->toOptionArray();
            $map = [];
            foreach ($options as $opt) { $map[$opt['value']] = $opt['label']; }
            foreach ($dataSource['data']['items'] as &$item) {
                $val = $item['mageworx_option_ids'] ?? null;
                if ($val && isset($map[$val])) {
                    $item['mageworx_option_ids'] = $map[$val];
                }
            }
        }
        return $dataSource;
    }
}
