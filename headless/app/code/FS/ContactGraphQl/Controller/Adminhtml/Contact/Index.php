<?php
namespace FS\ContactGraphQl\Controller\Adminhtml\Contact;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use FS\ContactGraphQl\Model\ContactFactory;
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
		
        $resultPage->getConfig()->getTitle()->prepend((__('Manage Contact Details')));
       // $this->_setActiveMenu('Tc_Company::company');
 
       // $resultPage->addBreadcrumb(__('modulename'), __('company'));
       // $resultPage->addBreadcrumb(__('modulename'), __('Manage Company Details'));
 
        return $resultPage;
    }
 
    //protected function _isAllowed()
    //{
     //   return $this->_authorization->isAllowed('Tc_Company::company');
    //}
}