<?php
/**
 * Flo_Configurator Attribute Repository
 *
 * @category  Flo
 * @package   Flo_Configurator
 * @author    Your Company
 * @copyright Copyright (c) 2025
 */

declare(strict_types=1);

namespace Flo\Configurator\Model;

use Flo\Configurator\Api\AttributeRepositoryInterface;
use Flo\Configurator\Api\Data\AttributeInterface;
use Flo\Configurator\Model\ResourceModel\Attribute as AttributeResource;
use Flo\Configurator\Model\ResourceModel\Attribute\CollectionFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class AttributeRepository implements AttributeRepositoryInterface
{
    /**
     * @var AttributeResource
     */
    private AttributeResource $resource;

    /**
     * @var AttributeFactory
     */
    private AttributeFactory $attributeFactory;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @var array
     */
    private array $instances = [];

    /**
     * Constructor
     *
     * @param AttributeResource $resource
     * @param AttributeFactory $attributeFactory
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        AttributeResource $resource,
        AttributeFactory $attributeFactory,
        CollectionFactory $collectionFactory
    ) {
        $this->resource = $resource;
        $this->attributeFactory = $attributeFactory;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $attributeId): AttributeInterface
    {
        if (!isset($this->instances[$attributeId])) {
            $attribute = $this->attributeFactory->create();
            $this->resource->load($attribute, $attributeId);
            
            if (!$attribute->getAttributeId()) {
                throw new NoSuchEntityException(
                    __('Configurator attribute with ID "%1" does not exist.', $attributeId)
                );
            }
            
            $this->instances[$attributeId] = $attribute;
        }
        
        return $this->instances[$attributeId];
    }

    /**
     * @inheritDoc
     */
    public function getByCode(string $attributeCode): AttributeInterface
    {
        $attribute = $this->attributeFactory->create();
        $this->resource->loadByCode($attribute, $attributeCode);
        
        if (!$attribute->getAttributeId()) {
            throw new NoSuchEntityException(
                __('Configurator attribute with code "%1" does not exist.', $attributeCode)
            );
        }
        
        return $attribute;
    }

    /**
     * @inheritDoc
     */
    public function getActiveAttributes(): array
    {
        $collection = $this->collectionFactory->create();
        $collection->addActiveFilter()
            ->setOrderBySortOrder();
        
        return $collection->getItems();
    }

    /**
     * @inheritDoc
     */
    public function save(AttributeInterface $attribute): AttributeInterface
    {
        try {
            $this->resource->save($attribute);
            unset($this->instances[$attribute->getAttributeId()]);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the configurator attribute: %1', $exception->getMessage()),
                $exception
            );
        }
        
        return $attribute;
    }

    /**
     * @inheritDoc
     */
    public function delete(AttributeInterface $attribute): bool
    {
        try {
            $attributeId = $attribute->getAttributeId();
            $this->resource->delete($attribute);
            unset($this->instances[$attributeId]);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not delete the configurator attribute: %1', $exception->getMessage()),
                $exception
            );
        }
        
        return true;
    }
}