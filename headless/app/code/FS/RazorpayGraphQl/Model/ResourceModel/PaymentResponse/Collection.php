<?php
namespace FS\RazorpayGraphQl\Model\ResourceModel\PaymentResponse;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'id';
    protected function _construct()
    {
        $this->_init('FS\RazorpayGraphQl\Model\PaymentResponse', 'FS\RazorpayGraphQl\Model\ResourceModel\PaymentResponse');
    }
}