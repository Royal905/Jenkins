<?php

namespace FS\SocialIcons\Model;

use Magento\Framework\Model\AbstractModel;

class SocialIcons extends AbstractModel
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init('FS\SocialIcons\Model\ResourceModel\SocialIcons');
    }
}