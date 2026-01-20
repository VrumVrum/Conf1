<?php
namespace Flo\Configurator\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\RequestInterface;
use Psr\Log\LoggerInterface;

class SetCustomPrice implements ObserverInterface
{
    protected $resource;
    protected $request;
    protected $logger;

    public function __construct(
        ResourceConnection $resource,
        RequestInterface $request,
        LoggerInterface $logger
    ) {
        $this->resource = $resource;
        $this->request = $request;
        $this->logger = $logger;
    }

    public function execute(Observer $observer)
    {
        try {
            $item = $observer->getEvent()->getQuoteItem();
            $params = $this->request->getParams();

            if (isset($params['flo_device_id']) && $params['flo_device_id']) {
                $connection = $this->resource->getConnection();
                $tableName = $this->resource->getTableName('flo_configurator_option');
                
                $totalPrice = 0;
                $additionalOptions = [];

                $selections = [
                    'Device' => $params['flo_device_id'],
                    'Collection' => $params['flo_collection_id'] ?? null,
                    'Color' => $params['flo_color_id'] ?? null
                ];

                foreach ($selections as $label => $id) {
                    if (!$id) continue;
                    
                    $data = $connection->fetchRow(
                        $connection->select()->from($tableName)->where('option_id = ?', (int)$id)
                    );

                    if ($data) {
                        $totalPrice += (float)$data['price'];
                        $additionalOptions[] = [
                            'label' => $label,
                            'value' => $data['label']
                        ];
                    }
                }

                if ($totalPrice > 0) {
                    $item->setCustomPrice($totalPrice);
                    $item->setOriginalCustomPrice($totalPrice);
                    $item->getProduct()->setIsSuperMode(true);

                    $item->addOption([
                        'product_id' => $item->getProductId(),
                        'code' => 'additional_options',
                        'value' => json_encode($additionalOptions)
                    ]);
                }
            }
        } catch (\Exception $e) {
            $this->logger->error('Flo_Configurator Observer Error: ' . $e->getMessage());
        }
    }
}
