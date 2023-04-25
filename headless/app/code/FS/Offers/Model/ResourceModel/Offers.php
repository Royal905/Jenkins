<?php

namespace FS\Offers\Model\ResourceModel;

class Offers extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('fs_offers', 'offers_id');   //here "fs_offers" is table name and "offers_id" is the primary key of custom table
    }
}