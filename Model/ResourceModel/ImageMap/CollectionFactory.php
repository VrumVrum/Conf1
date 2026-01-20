<?php
namespace Flo\Configurator\Model\ResourceModel\ImageMap;

class CollectionFactory
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager)
    {
        $this->_objectManager = $objectManager;
    }

    /**
     * Create class instance with specified parameters
     *
     * @param array $data
     * @return \Flo\Configurator\Model\ResourceModel\ImageMap\Collection
     */
    public function create(array $data = [])
    {
        return $this->_objectManager->create(\Flo\Configurator\Model\ResourceModel\ImageMap\Collection::class, $data);
    }
}
