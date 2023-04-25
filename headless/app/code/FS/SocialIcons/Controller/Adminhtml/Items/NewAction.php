<?php

namespace FS\SocialIcons\Controller\Adminhtml\Items;

class NewAction extends \FS\SocialIcons\Controller\Adminhtml\Items
{

    public function execute()
    {
        $this->_forward('edit');
    }
}
