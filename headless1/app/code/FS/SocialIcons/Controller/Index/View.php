<?php

namespace FS\SocialIcons\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\NotFoundException;
use FS\SocialIcons\Block\SocialIconsView;

class View extends \Magento\Framework\App\Action\Action
{
	protected $_socialiconsview;

	public function __construct(
        Context $context,
        SocialIconsView $socialiconsview
    ) {
        $this->_socialiconsview = $socialiconsview;
        parent::__construct($context);
    }

	public function execute()
    {
    	if(!$this->_socialiconsview->getSingleData()){
    		throw new NotFoundException(__('Parameter is incorrect.'));
    	}
    	
        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
    }
}
