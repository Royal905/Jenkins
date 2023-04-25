<?php

namespace FS\SocialIcons\Block;

use Magento\Framework\View\Element\Template\Context;
use FS\SocialIcons\Model\SocialIconsFactory;
use Magento\Cms\Model\Template\FilterProvider;
/**
 * SocialIcons View block
 */
class SocialIconsView extends \Magento\Framework\View\Element\Template
{
    /**
     * @var SocialIcons
     */
    protected $_socialicons;
    public function __construct(
        Context $context,
        SocialIconsFactory $socialicons,
        FilterProvider $filterProvider
    ) {
        $this->_socialicons = $socialicons;
        $this->_filterProvider = $filterProvider;
        parent::__construct($context);
    }

    public function _prepareLayout()
    {
        $this->pageConfig->getTitle()->set(__('FS SocialIcons Module View Page'));
        
        return parent::_prepareLayout();
    }

    public function getSingleData()
    {
        $id = $this->getRequest()->getParam('id');
        $socialicons = $this->_socialicons->create();
        $singleData = $socialicons->load($id);
        if($singleData->getSocialIconsId() && $singleData->getStatus() == 1){
            return $singleData;
        }else{
            return false;
        }
    }
}