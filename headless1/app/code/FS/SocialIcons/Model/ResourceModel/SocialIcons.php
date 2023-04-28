<?php

namespace FS\SocialIcons\Model\ResourceModel;

class SocialIcons extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('fs_socialicons', 'socialicons_id');   //here "fs_socialicons" is table name and "socialicons_id" is the primary key of custom table
    }
}