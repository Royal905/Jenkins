<?php

namespace FS\Offers\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\NotFoundException;
use FS\Offers\Block\OffersView;

class View extends \Magento\Framework\App\Action\Action
{
	protected $_offersview;

	public function __construct(
        Context $context,
        OffersView $offersview
    ) {
        $this->_offersview = $offersview;
        parent::__construct($context);
    }

	public function execute()
    {
    	if(!$this->_offersview->getSingleData()){
    		throw new NotFoundException(__('Parameter is incorrect.'));
    	}
    	
        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
    }
}
