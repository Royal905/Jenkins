<?php
namespace FS\RazorpayGraphQl\Controller\Adminhtml\Logs;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Action;

class Index extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;
        protected $_publicActions = ['index'];
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
     
    public function execute() {        

        $resultPage = $this->resultPageFactory->create();
		
        $resultPage->getConfig()->getTitle()->prepend((__('Display Logs')));
      
        return $resultPage;
    }
 
}