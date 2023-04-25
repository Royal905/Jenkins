<?php 
namespace FS\RazorpayGraphQl\Model;
use \Magento\Framework\Model\AbstractModel;
class PaymentResponse extends AbstractModel
{   
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }
    protected function _construct()
    {
        $this->_init('FS\RazorpayGraphQl\Model\ResourceModel\PaymentResponse');
    }
}