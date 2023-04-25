<?php

namespace FS\SocialIcons\Block;

use Magento\Framework\View\Element\Template\Context;
use FS\SocialIcons\Model\SocialIconsFactory;
/**
 * SocialIcons List block
 */
class SocialIconsListData extends \Magento\Framework\View\Element\Template
{
    /**
     * @var SocialIcons
     */
    protected $_socialicons;
    public function __construct(
        Context $context,
        SocialIconsFactory $socialicons
    ) {
        $this->_socialicons = $socialicons;
        parent::__construct($context);
    }

    public function _prepareLayout()
    {
        $this->pageConfig->getTitle()->set(__('FS SocialIcons Module List Page'));
        
        if ($this->getSocialIconsCollection()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'fssocialicons.socialicons.pager'
            )->setAvailableLimit(array(5=>5,10=>10,15=>15))->setShowPerPage(true)->setCollection(
                $this->getSocialIconsCollection()
            );
            $this->setChild('pager', $pager);
            $this->getSocialIconsCollection()->load();
        }
        return parent::_prepareLayout();
    }

    public function getSocialIconsCollection()
    {
        $page = ($this->getRequest()->getParam('p'))? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit'))? $this->getRequest()->getParam('limit') : 5;

        $socialicons = $this->_socialicons->create();
        $collection = $socialicons->getCollection();
        $collection->addFieldToFilter('status','1');
        //$socialicons->setOrder('socialicons_id','ASC');
        $collection->setPageSize($pageSize);
        $collection->setCurPage($page);

        return $collection;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}