<?php

  namespace FS\RazorpayGraphQl\Controller\Response;

  use Magento\Framework\App\CsrfAwareActionInterface;
  use Magento\Framework\App\Request\InvalidRequestException;
  use Magento\Framework\App\RequestInterface;
  use \Magento\Framework\App\Action\Context;
  use FS\RazorpayGraphQl\Model\PaymentResponseFactory;
  use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;

class Hook extends \Magento\Framework\App\Action\Action implements CsrfAwareActionInterface{

      /**
       * @param Context $context
       */
    /**
       * @param Context $paymentresponse
       */

          private $scopeConfig;
          
           protected $_logger;

          public function __construct(
             Context $context,
             PaymentResponseFactory $paymentresponse,
        	 \Psr\Log\LoggerInterface $logger,
             \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
         ){
             $this->paymentresponse = $paymentresponse;
        	 $this->_logger = $logger;
             $this->scopeConfig = $scopeConfig;
             parent::__construct($context);
         }

         public function execute(){

             $responseData = $_POST;

             echo '<pre>'; print_r($responseData); die;
              $this->_logger->debug("Razorpay webhook received");

        	  $this->_logger->debug(json_encode($responseData));



        }

        public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
        {
         return null;
        }

        public function validateForCsrf(RequestInterface $request): ?bool
        {
           return true;
        }



  }