<?php
namespace Flo\Configurator\Ui\DataProvider;

use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;
use Magento\Framework\App\ObjectManager;

class ImageListingProvider extends DataProvider
{
    public function getData()
    {
        $data = parent::getData();
        if (isset($data['items']) && is_array($data['items'])) {
            $objectManager = ObjectManager::getInstance();
            $optionCollection = $objectManager->create(\Flo\Configurator\Model\ResourceModel\Option\Collection::class);
            $labels = [];
            foreach ($optionCollection as $option) {
                $labels[$option->getId()] = $option->getLabel();
            }

            foreach ($data['items'] as &$item) {
                // Forțăm string-uri goale pentru a evita moștenirea datelor de la rândul anterior în UI
                $item['device_label'] = $labels[$item['device_type_option_id'] ?? 0] ?? '';
                $item['collection_label'] = $labels[$item['collection_option_id'] ?? 0] ?? '';
                $item['color_label'] = $labels[$item['color_option_id'] ?? 0] ?? '';
                
                // Debug pentru a vedea ID-ul real în consolă dacă e nevoie
                $item['id_field_name'] = 'image_id';
            }
        }
        return $data;
    }
}
