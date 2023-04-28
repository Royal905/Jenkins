<?php

namespace Cinovic\Otplogin\Model;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;

class Token 
{
    /**
    * @var \Magento\Customer\Model\Session
    */
    protected $_customerSession;

    /**
    * @param Context          $context
    * @param Session          $customerSession
    * @SuppressWarnings(PHPMD.ExcessiveParameterList)
    */
    public function __construct(
        Session $customerSession,
        \Magento\Integration\Model\Oauth\TokenFactory $tokenModelFactory
    ) {
        $this->_customerSession = $customerSession;
        $this->_tokenModelFactory = $tokenModelFactory;
       
    }

    public function getToken($customerId)
    {
        $tokenKey  = null;
        $customerToken = $this->_tokenModelFactory->create();
        $tokenKey = $customerToken->createCustomerToken($customerId)->getToken();

        return $tokenKey; 
    }
}