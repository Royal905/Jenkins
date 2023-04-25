<?php
declare(strict_types=1);
 
namespace FS\RazorpayGraphQl\Model\Resolver\DataProvider;
 

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
 
class PaymentStatus
{
 
    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

     /**
     * @var DataObjectHelper
     */
    private $scopeConfig;

    /**
     * Constructor
     *
     * @param Session $checkoutSession
     */

   private $paymentResponseFactory;
     
    public function __construct(
        DataObjectHelper $dataObjectHelper,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
         \FS\RazorpayGraphQl\Model\PaymentResponseFactory $paymentResponseFactory
    ) {
        $this->dataObjectHelper = $dataObjectHelper;
          $this->scopeConfig = $scopeConfig;
            $this->paymentResponseFactory = $paymentResponseFactory;
       
    }
 
    /**
     * @param array $data
     * @return StoreInterface
     * @throws GraphQlInputException
     */
    public function execute(array $data)
    {
        try {
            $data = $this->checkPaymentStatus($data);
            return $data;
          
        } catch (\Exception $e) {
            throw new GraphQlInputException(__($e->getMessage()));
        }
 
       
    }


    public function getConfig($config_key){
        return $this->scopeConfig->getValue('payment/pinepgpaymentmethod/'.$config_key, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }



    public function checkPaymentStatus($data){



            
           
        
        $transaction_id = $data['transaction_id'];

         $response = array(
                    'status'=>'NOT AVAILABLE',
                    'transaction_id'=>$transaction_id
            );

        $paymentresponse = $this->paymentResponseFactory->create()->load($transaction_id, 'transaction_id');

        if($paymentresponse->getResponseData()){

            $paymentresponseData = json_decode($paymentresponse->getResponseData(),TRUE);

               // echo '<pre>'; print_r($paymentresponseData); die;
            $status  = $paymentresponseData['pine_pg_txn_status'];
            $message = "Transaction failed.";
            if($status==4){
		$message = "Transaction successfull.";
	    }
	    if($status==-10){
		$message = "Transaction cancelled.";
	    }

            $response = array(
                    'status'=>$status,
                    'transaction_id'=>$paymentresponseData['unique_merchant_txn_id'],
		    'message'=>$message
            );

        }

       

      
     

        return $response;



            //return  $hash;


    }

   
   
}