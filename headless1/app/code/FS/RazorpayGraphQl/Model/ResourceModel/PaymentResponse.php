<?php

namespace FS\RazorpayGraphQl\Model\ResourceModel;

class PaymentResponse extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('razorpay_response', 'id');
    }
}