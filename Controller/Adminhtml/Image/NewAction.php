<?php
declare(strict_types=1);
namespace Flo\Configurator\Controller\Adminhtml\Image;
class NewAction extends \Magento\Backend\App\Action {
    public function execute() { return $this->_forward('edit'); }
}
