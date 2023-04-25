<?php

namespace FS\RazorpayGraphQl\Helper;
use \Magento\Framework\App\Helper\AbstractHelper;
use \Magento\Framework\App\Helper\Context;
use Magento\Sales\Model\Order;
use Magento\Framework\Translate\Inline\StateInterface;

use Magento\Framework\Mail\Template\TransportBuilder;

class Data extends AbstractHelper
{
    /**
     * @var \Magento\Framework\HTTP\Client\Curl
     */
    protected $_curl;
    protected $scopeConfig;
    protected $orderFactory;
     protected $transportBuilder;
    protected $storeManager;
    protected $inlineTranslation;
    private $paymentResponseFactory;
    
    public function __construct(Context $context, 
	\Magento\Sales\Model\OrderFactory $orderFactory,
	TransportBuilder $transportBuilder,     
	StateInterface $state,
	\Magento\Framework\HTTP\Client\Curl $curl,
	 \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
	 \FS\RazorpayGraphQl\Model\PaymentResponseFactory $paymentResponseFactory)
    {
        $this->_curl       = $curl;
        $this->scopeConfig = $scopeConfig;
       
        $this->paymentResponseFactory = $paymentResponseFactory;
	$this->orderFactory= $orderFactory;
	$this->transportBuilder = $transportBuilder;
	
        $this->inlineTranslation = $state;
        
        parent::__construct($context);
    }
    
    
    public function inquireStatus($transaction_id = NULL)
    {
        if($transaction_id) {
            $response = $this->_callPinePgGateway($transaction_id);
            return $response;
        }
        return null;
    }
    
    
    public function _callPinePgGateway($transaction_id)
    {
        
        
        
        $ppc_MerchantID         = $this->getConfig('MerchantId');
        $ppc_MerchantAccessCode = $this->getConfig('MerchantAccessCode');
        $secret_key             = $this->getConfig('MerchantSecretKey');
        $secret_key             = $this->hex2String($secret_key);
        
        
        $request_data = array(
            'ppc_MerchantAccessCode' => $ppc_MerchantAccessCode,
            'ppc_MerchantID' => $ppc_MerchantID,
            'ppc_TransactionType' => 3,
            'ppc_UniqueMerchantTxnID' => $transaction_id
        );
        

        
      

        ksort($request_data);
        
        $strFormdata = "";
        foreach ($request_data as $key => $val) {
            $strFormdata .= $key . "=" . $val . "&";
        }
        
        
        $strFormdata = substr($strFormdata, 0, -1);
        
 	$encoded_request_data  = base64_encode($strFormdata);
        
        $hash = strtoupper(hash_hmac('sha256', $strFormdata, $secret_key));
        
            
        $request_data['ppc_DIA_SECRET_TYPE'] = 'SHA256';
        $request_data['ppc_DIA_SECRET']      = $hash;
        
        
        $responsedata = array(
            'response_code' => 0,
            'response_message' => "CURL FAILURE"
        );
        
        
        $payEnvironment = $this->getConfig('PayEnvironment');
        if ($payEnvironment == 'TEST') {
            $gateway_url = $this->getConfig('sandbox_url_inquiry');
        }
        if ($payEnvironment == 'LIVE') {
            $gateway_url = $this->getConfig('production_url_inquiry');
        }

	

	
        $headers = array(
            'Content-Type: application/json',
            'X-VERIFY: '.$hash
        );

        $post_data = json_encode(array("request"=>$encoded_request_data));

	//echo '<pre>'; echo $hash; print_r($headers);  print_r($request_data); print_r($post_data); die;

        $ch = curl_init();
        


        //echo http_build_query($request_data); die;
        curl_setopt($ch, CURLOPT_URL, $gateway_url);
       
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); 
        
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);        

        $headers   = array();
   
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        
        $response = curl_exec($ch);        
        
	echo '<pre>'; print_r($response); die;
       
        if (curl_errno($ch)) {
            $error_msg    = curl_error($ch);

            $responsedata = array(
                'response_code' => 0,
                'response_message' => $error_msg
            );
        } else {
            $responsedata = json_decode($response, true);
            
        }
        curl_close($ch);
        
    
        return $responsedata;
        
    }
    
    public function getConfig($config_key)
    {
        return $this->scopeConfig->getValue('payment/pinepgpaymentmethod/' . $config_key, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
     

    
    public function hex2String($hex)
    {
        $string = '';
        
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }
        
        return $string;
    }

   public function updateOrderStatus($unique_merchant_txn_id){

        
        $order =$this->orderFactory->create()->load($unique_merchant_txn_id,'unique_merchant_txn_id');
        if($order){
        	$orderState = Order::STATE_PROCESSING;
        	$order->setState($orderState)->setStatus($orderState);
        	$order->save();
		$this->sendOrderEmail($order->getId());
        }
    }
 	public function updateFailedOrderStatus($unique_merchant_txn_id){

        
        	$order =$this->orderFactory->create()->load($unique_merchant_txn_id,'unique_merchant_txn_id');
        	if($order){
			$this->sendOrderEmail($order->getId(),'failed');
        	}
    	}


	public function sendOrderEmail($orderId,$status='success'){
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		//$state = $objectManager->get('Magento\Framework\App\State');
		//$state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);
		$order = $objectManager->create('\Magento\Sales\Model\Order')->load($orderId);

        	if($status=='success'){
			$order->setCanSendNewEmailFlag(true);
			$order->save();
			$session = $objectManager->create('\Magento\Checkout\Model\Session');
			$session->setForceOrderMailSentOnSuccess(true);
			$emailSender = $objectManager->create('\Magento\Sales\Model\Order\Email\Sender\OrderSender');
			$emailSender->send($order);
		}else{
			$this->sendFailedPaymentEmail($order);
		}
	}

   public function sendFailedPaymentEmail($order=null) {
        
        $this->inlineTranslation->suspend();
       
	//echo '<pre>'; print_r($order->getData()); die;
        $template = 'failed_order_email_template'; //$this->scopeConfig->getValue('checkout/payment_failed/template', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
 
        $receiver = $this->scopeConfig->getValue('checkout/payment_failed/receiver',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $sendTo = [
            [
                'email' => $order->getCustomerEmail(),
                'name' => $order->getCustomerFirstname()
            ],
        ];

	//echo '<pre>'; print_r($sendTo); 

        $copyMethod = $this->scopeConfig->getValue('checkout/payment_failed/copy_method', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

 	$configData = $this->scopeConfig->getValue('checkout/payment_failed/copy_to', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        if (!empty($configData)) {
           $copyTo = explode(',', $configData);
        }

       

        $bcc = [];
        if (!empty($copyTo)) {
            switch ($copyMethod) {
                case 'bcc':
                    $bcc = $copyTo;
                    break;
                case 'copy':
                    foreach ($copyTo as $email) {
                        $sendTo[] = ['email' => $email, 'name' => null];
                    }
                    break;
            }
        }
	//echo '<pre>'; print_r($sendTo); die;

	$identity = $this->scopeConfig->getValue('checkout/payment_failed/identity',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);

	$from = [
		'email' => $this->scopeConfig->getValue('trans_email/ident_'.$identity.'/email',\Magento\Store\Model\ScopeInterface::SCOPE_STORE),
		'name' => $this->scopeConfig->getValue('trans_email/ident_'.$identity.'/name',\Magento\Store\Model\ScopeInterface::SCOPE_STORE)
	];
	
 	$templateVars = [
                'unique_merchant_txn_id' => 'test',
		'reason'=>'Payment Failed',
		'checkoutType'=>'onepage',
		'customer_name'=>$order->getCustomerFirstname()
         ];
        foreach ($sendTo as $recipient) {
            $transport = $this->transportBuilder
                ->setTemplateIdentifier($template)
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars($templateVars)
                ->setFrom($from)
                ->addTo($recipient['email'], $recipient['name'])
                ->addBcc($bcc)
                ->getTransport();

            try {
                $transport->sendMessage();
            } catch (\Exception $e) {
                $this->logger->critical($e->getMessage());
            }
        }

        $this->inlineTranslation->resume();

        return $this;
    }






    
    
}