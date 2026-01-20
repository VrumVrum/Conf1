<?php
namespace Flo\Configurator\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

class AttributeName extends Column
{
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $mapping = [
                1 => 'Device Type',
                2 => 'Collection',
                3 => 'Color'
            ];
            foreach ($dataSource['data']['items'] as &$item) {
                $attrId = $item['attribute_id'];
                $item['attribute_id'] = isset($mapping[$attrId]) 
                    ? __($mapping[$attrId]) 
                    : __('Unknown (%1)', $attrId);
            }
        }
        return $dataSource;
    }
}
