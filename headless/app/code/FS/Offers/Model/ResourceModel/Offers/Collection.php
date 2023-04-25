<?php

namespace FS\Offers\Model\ResourceModel\Offers;
 
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'offers_id';
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'FS\Offers\Model\Offers',
            'FS\Offers\Model\ResourceModel\Offers'
        );
    }
}