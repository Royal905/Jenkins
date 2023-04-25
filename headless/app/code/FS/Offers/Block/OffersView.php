<?php

namespace FS\Offers\Block;

use Magento\Framework\View\Element\Template\Context;
use FS\Offers\Model\OffersFactory;
use Magento\Cms\Model\Template\FilterProvider;
/**
 * Offers View block
 */
class OffersView extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Offers
     */
    protected $_offers;
    public function __construct(
        Context $context,
        OffersFactory $offers,
        FilterProvider $filterProvider
    ) {
        $this->_offers = $offers;
        $this->_filterProvider = $filterProvider;
        parent::__construct($context);
    }

    public function _prepareLayout()
    {
        $this->pageConfig->getTitle()->set(__('FS Offers Module View Page'));
        
        return parent::_prepareLayout();
    }

    public function getSingleData()
    {
        $id = $this->getRequest()->getParam('id');
        $offers = $this->_offers->create();
        $singleData = $offers->load($id);
        if($singleData->getOffersId() && $singleData->getStatus() == 1){
            return $singleData;
        }else{
            return false;
        }
    }
}