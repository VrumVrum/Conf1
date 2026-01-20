<?php
namespace Flo\Configurator\Model\Source\Image;
class CollectionOptions extends \Flo\Configurator\Model\Source\Options {
    public function __construct(\Flo\Configurator\Model\ResourceModel\Option\CollectionFactory $factory) {
        parent::__construct($factory, 'collection');
    }
}
