<?php
namespace Flo\Configurator\Model\ResourceModel\MageworxRule\Grid;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class Collection extends SearchResult
{
    protected function _initSelect()
    {
        parent::_initSelect();
        return $this;
    }
}
