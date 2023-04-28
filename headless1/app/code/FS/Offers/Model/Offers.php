<?php

namespace FS\Offers\Model;

use Magento\Framework\Model\AbstractModel;

class Offers extends AbstractModel
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init('FS\Offers\Model\ResourceModel\Offers');
    }
}