<?php
namespace FS\RazorpayGraphQl\ViewModel;


use Magento\Framework\App\Request\Http;

class PaymentInfo implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
  
    protected $request;
    protected $order;

    protected $paymentResponseFactory;

    public function __construct(
        Http $request,
        \Magento\Sales\Api\OrderRepositoryInterface $order,
        \FS\RazorpayGraphQl\Model\PaymentResponseFactory $paymentResponseFactory
    ) {
   
        $this->request = $request;
        $this->order = $order;
        $this->paymentResponseFactory = $paymentResponseFactory;
    }
    public function getPaymentInformation()
    {
        
        $order_id = $this->request->getParam('order_id');
        $order = $this->order->get($order_id);
         $payment = $order->getPayment();

        return $payment->getAdditionalInformation();
    }

        public function getOrderId(){
        
        return $this->request->getParam('order_id');
    }

    public function getTransactionInformation($transaction_id){
    
         $paymentresponse = $this->paymentResponseFactory->create()->load($transaction_id, 'transaction_id');
         //var_dump($transaction_id); var_dump($paymentresponse->getResponseData()); die;
        if($paymentresponse->getResponseData()){
            return json_decode($paymentresponse->getResponseData(),TRUE);
        }
        return [];
    }

      public function getUniqueMerchantTxnId(){
        
        $order_id = $this->request->getParam('order_id');
        $order = $this->order->get($order_id);
   

        return $order->getUniqueMerchantTxnId();
    }
	
	

}