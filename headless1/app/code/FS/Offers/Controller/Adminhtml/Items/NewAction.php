<?php

namespace FS\Offers\Controller\Adminhtml\Items;

class NewAction extends \FS\Offers\Controller\Adminhtml\Items
{

    public function execute()
    {
        $this->_forward('edit');
    }
}
