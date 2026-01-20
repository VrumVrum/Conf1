<?php
namespace Flo\Configurator\Model\Configurator\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\App\ResourceConnection;

class Options implements OptionSourceInterface
{
    protected $resource;
    protected $attributeId;

    public function __construct(ResourceConnection $resource, $attributeId = null) {
        $this->resource = $resource;
        $this->attributeId = $attributeId;
    }

    public function toOptionArray() {
        $data = $this->_getOptions();
        $options = [];
        foreach ($data as $id => $label) {
            $options[] = ['value' => $id, 'label' => $label];
        }
        return $options;
    }

    public function getOptionMap() {
        return $this->_getOptions();
    }

    protected function _getOptions() {
        $connection = $this->resource->getConnection();
        $tableName = $this->resource->getTableName('flo_configurator_option');
        $query = $connection->select()->from($tableName, ['option_id', 'label'])->where('is_active = ?', 1);
        if ($this->attributeId) { $query->where('attribute_id = ?', (int)$this->attributeId); }
        return $connection->fetchPairs($query);
    }
}
