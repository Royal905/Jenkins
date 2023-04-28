<?php
declare(strict_types=1);

namespace FS\RazorpayGraphQl\Model\Resolver\DataProvider;


use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Sales\Model\Order;


class UpdatePaymentResponse
{

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    protected $orderRepository;

    protected $orderCollectionFactory;
    protected $order;


    public function __construct(
        DataObjectHelper $dataObjectHelper,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
                \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
                Order $order

    ) {
        $this->dataObjectHelper = $dataObjectHelper;
        $this->orderRepository = $orderRepository;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->order = $order;

    }

    /**
     * @param array $data
     * @return StoreInterface
     * @throws GraphQlInputException
     */
    public function execute(array $data)
    {

        if(isset($data['data'])){

            $order_id = $data['data'][0]['order_id'];
        }



//echo  '<pre>'; print_r($data); die;

        if(isset($data['data'][0]['payment_response'])){

            $payment_response = $data['data'][0]['payment_response'];
            $paymentData = json_decode($payment_response, TRUE) ;

            //echo  '<pre>'; print_r($paymentData); die;

            if(isset($paymentData['razorpay_order_id'])){

                $collection = $this->orderCollectionFactory->create()
                            ->addFieldToFilter('rzp_order_id',$paymentData['razorpay_order_id']);

                            

                if($collection->count()){
                    $order_id = $collection->getFirstItem()->getId();


                    $order = $this->getOrder($order_id);

                    if(isset($paymentData['razorpay_payment_id'])){
                        $orderState = Order::STATE_PROCESSING;
                        $order->setState($orderState)->setStatus($orderState);
                        $order->addStatusHistoryComment('Order confirmed from razor pay Payment ID #'. $paymentData['razorpay_payment_id']);
                        $order->save();

                    }
                }

            }

             if(isset($paymentData['error'])){

                $collection = $this->orderCollectionFactory->create()
                            ->addFieldToFilter('rzp_order_id',$paymentData['error']['metadata']['order_id']);
                if($collection->count()){
                    $order_id = $collection->getFirstItem()->getId();

                    $order = $this->getOrder($order_id);

                    if(isset($paymentData['error']['metadata']['payment_id']) && $order->getId()){

                        $order->addStatusHistoryComment('Order failed from razor pay. Payment ID #'. $paymentData['error']['metadata']['payment_id']);

                        $orderState = Order::STATE_PROCESSING;
                        $order->setState($orderState)->setStatus($orderState);
                        $order->save();

                    }
                }

            }

        }

        


        



        try {   


     if($order){

       $grandTotal = $order->getGrandTotal();

       

       $orderItems = $order->getAllVisibleItems();

       $items = array();

       foreach ($orderItems as $item) {
           $items[] =   array(
              "name"=>$item->getName(),
              "sku"=>$item->getSku(),
              "image"=>"",
              "price"=>$item->getPrice(),
              "qty_ordered"=>$item->getQtyOrdered()
          );
       }

       $response =  array(
        'status'=>$order->getStatus(),
        'order_total'=>$grandTotal,
        'subtotal'=>$order->getSubtotal(),
        'shipping_amount'=>$order->getShippingAmount(),
        'tax_amount'=>$order->getTaxAmount(),
        'message'=>'Thanks for the order',
        'items'=>$items
    );
}else{

    $response =  array(
        'status'=>0,
        'order_total'=>0.00,
        'subtotal'=>0.00,
        'shipping_amount'=>0.00,
        'tax_amount'=>0.00,
        'message'=>'Unable to fetch  the order',
        'items'=>array()
    );
}
       return  $response;


   } catch (\Exception $e) {
    throw new GraphQlInputException(__($e->getMessage()));
}


}

public function getOrder($orderId)
{


    $order = $this->order->load($orderId);
    return $order;
    
}


}
