<?php

namespace FS\SocialIcons\Model\ResourceModel\SocialIcons;
 
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'socialicons_id';
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'FS\SocialIcons\Model\SocialIcons',
            'FS\SocialIcons\Model\ResourceModel\SocialIcons'
        );
    }
}