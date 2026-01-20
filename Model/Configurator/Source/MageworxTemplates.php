<?php
namespace Flo\Configurator\Model\Configurator\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\App\ResourceConnection;

class MageworxTemplates implements OptionSourceInterface
{
    protected $resource;

    public function __construct(ResourceConnection $resource) {
        $this->resource = $resource;
    }

    public function toOptionArray() {
        $connection = $this->resource->getConnection();
        $options = [];
        
        // Incercam numele tabelului de grupuri de template-uri
        $tableName = $this->resource->getTableName('mageworx_optiontemplates_group');
        
        if (!$connection->isTableExists($tableName)) {
            // Alternativa daca prima varianta nu exista
            $tableName = $this->resource->getTableName('mageworx_optionfeatures_option_template');
        }

        try {
            if ($connection->isTableExists($tableName)) {
                // In tabelele Mageworx, coloanele sunt de obicei group_id si title
                // sau entity_id si name. Verificam title/name.
                $query = $connection->select()->from($tableName, ['value' => 'group_id', 'label' => 'title']);
                $data = $connection->fetchAll($query);
                foreach ($data as $row) {
                    $options[] = [
                        'value' => $row['value'],
                        'label' => $row['label']
                    ];
                }
            }
        } catch (\Exception $e) {
            // Log error or fallback
        }
        
        return $options;
    }
}
