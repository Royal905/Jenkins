<?php

namespace FS\Offers\Block;

use Magento\Framework\View\Element\Template\Context;
use FS\Offers\Model\OffersFactory;
/**
 * Offers List block
 */
class OffersListData extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Offers
     */
    protected $_offers;
    public function __construct(
        Context $context,
        OffersFactory $offers
    ) {
        $this->_offers = $offers;
        parent::__construct($context);
    }

    public function _prepareLayout()
    {
        $this->pageConfig->getTitle()->set(__('FS Offers Module List Page'));
        
        if ($this->getOffersCollection()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'fsoffers.offers.pager'
            )->setAvailableLimit(array(5=>5,10=>10,15=>15))->setShowPerPage(true)->setCollection(
                $this->getOffersCollection()
            );
            $this->setChild('pager', $pager);
            $this->getOffersCollection()->load();
        }
        return parent::_prepareLayout();
    }

    public function getOffersCollection()
    {
        $page = ($this->getRequest()->getParam('p'))? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit'))? $this->getRequest()->getParam('limit') : 5;

        $offers = $this->_offers->create();
        $collection = $offers->getCollection();
        $collection->addFieldToFilter('status','1');
        //$offers->setOrder('offers_id','ASC');
        $collection->setPageSize($pageSize);
        $collection->setCurPage($page);

        return $collection;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}