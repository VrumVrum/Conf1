<?php
/**
 * Flo_Configurator Image Resolver Service
 *
 * @category  Flo
 * @package   Flo_Configurator
 * @author    Your Company
 * @copyright Copyright (c) 2025
 */

declare(strict_types=1);

namespace Flo\Configurator\Service;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class ImageResolver
{
    /**
     * @var ResourceConnection
     */
    private ResourceConnection $resource;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Constructor
     *
     * @param ResourceConnection $resource
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        ResourceConnection $resource,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger
    ) {
        $this->resource = $resource;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
    }

    /**
     * Get image URL based on current configuration
     *
     * Priority: collection+color > collection > device_type > generic
     *
     * @param array $configuration
     * @return string|null
     */
    public function resolveImage(array $configuration): ?string
    {
        try {
            $connection = $this->resource->getConnection();
            $imageMapTable = $this->resource->getTableName('flo_configurator_image_map');

            $deviceTypeId = $configuration['device_type'] ?? null;
            $collectionId = $configuration['collection'] ?? null;
            $colorId = $configuration['color'] ?? null;

            // Priority 1: collection + color
            if ($collectionId && $colorId) {
                $select = $connection->select()
                    ->from($imageMapTable, 'image_path')
                    ->where('collection_option_id = ?', $collectionId)
                    ->where('color_option_id = ?', $colorId)
                    ->order('priority DESC')
                    ->limit(1);

                $imagePath = $connection->fetchOne($select);
                if ($imagePath) {
                    return $this->getImageUrl($imagePath);
                }
            }

            // Priority 2: collection only
            if ($collectionId) {
                $select = $connection->select()
                    ->from($imageMapTable, 'image_path')
                    ->where('collection_option_id = ?', $collectionId)
                    ->where('color_option_id IS NULL')
                    ->order('priority DESC')
                    ->limit(1);

                $imagePath = $connection->fetchOne($select);
                if ($imagePath) {
                    return $this->getImageUrl($imagePath);
                }
            }

            // Priority 3: device type only
            if ($deviceTypeId) {
                $select = $connection->select()
                    ->from($imageMapTable, 'image_path')
                    ->where('device_type_option_id = ?', $deviceTypeId)
                    ->where('collection_option_id IS NULL')
                    ->where('color_option_id IS NULL')
                    ->order('priority DESC')
                    ->limit(1);

                $imagePath = $connection->fetchOne($select);
                if ($imagePath) {
                    return $this->getImageUrl($imagePath);
                }
            }

            // Priority 4: generic image (all NULL)
            $select = $connection->select()
                ->from($imageMapTable, 'image_path')
                ->where('device_type_option_id IS NULL')
                ->where('collection_option_id IS NULL')
                ->where('color_option_id IS NULL')
                ->order('priority DESC')
                ->limit(1);

            $imagePath = $connection->fetchOne($select);
            if ($imagePath) {
                return $this->getImageUrl($imagePath);
            }

            return null;

        } catch (\Exception $e) {
            $this->logger->error('Failed to resolve image', [
                'configuration' => $configuration,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Get full image URL from path
     *
     * @param string $imagePath
     * @return string
     */
    private function getImageUrl(string $imagePath): string
    {
        $mediaUrl = $this->storeManager->getStore()
            ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);

        return $mediaUrl . 'configurator/' . ltrim($imagePath, '/');
    }
}