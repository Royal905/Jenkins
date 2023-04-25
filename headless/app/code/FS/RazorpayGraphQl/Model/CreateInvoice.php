<?php

namespace FS\RazorpayGraphQl\Model;

use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Service\InvoiceService;
use Magento\Framework\DB\Transaction;
use Magento\Sales\Model\Order\Email\Sender\InvoiceSender;
use \Magento\Framework\Event\ObserverInterface;

class CreateInvoice implements ObserverInterface 
{
    protected $orderRepository;
    protected $invoiceService;
    protected $transaction;
    protected $invoiceSender;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        InvoiceService $invoiceService,
        InvoiceSender $invoiceSender,
        Transaction $transaction
    ) {
        $this->orderRepository = $orderRepository;
        $this->invoiceService = $invoiceService;
        $this->transaction = $transaction;
        $this->invoiceSender = $invoiceSender;
    }
    

	
    public function execute(\Magento\Framework\Event\Observer $observer){
        $shipment = $observer->getEvent()->getShipment();
        $order = $shipment->getOrder();
	
	if(!$order->hasInvoices()){
		$this->createInvoiceForOrder($order->getId());
	}
	
        
    }

  public function notifyInvoiceForOrder($orderId){
	
	$order = $this->orderRepository->get($orderId);

	if($order->hasInvoices()){
		$this->sendInvoiceEmailForOrder($orderId);
	}else{
		$this->createInvoiceForOrder($order->getId());
	}


    }


    public function createInvoiceForOrder($orderId)
    {
        $order = $this->orderRepository->get($orderId);
        

	
        if ($order->canInvoice()) {
            $invoice = $this->invoiceService->prepareInvoice($order);
            $invoice->register();
            $invoice->save();
            
            $transactionSave = 
                $this->transaction
                    ->addObject($invoice)
                    ->addObject($invoice->getOrder());
            $transactionSave->save();
            $this->invoiceSender->send($invoice);
            
            $order->addCommentToStatusHistory(
                __('Notified customer about invoice creation #%1.', $invoice->getId())
            )->setIsCustomerNotified(true)->save(); 
        }
    }


    public function sendInvoiceEmailForOrder($orderId)
    {
        $order = $this->orderRepository->get($orderId);
        
        if ($order->hasInvoices()) {
            $invoice = $this->invoiceService->prepareInvoice($order);
		/*
            $invoice->register();
            $invoice->save();
            
            $transactionSave = 
                $this->transaction
                    ->addObject($invoice)
                    ->addObject($invoice->getOrder());
            $transactionSave->save();
            */
		$this->invoiceSender->send($invoice);
            
            $order->addCommentToStatusHistory(
                __('Notified customer about invoice creation #%1.', $invoice->getId())
            )->setIsCustomerNotified(true)->save(); 
        }
    }


    

}