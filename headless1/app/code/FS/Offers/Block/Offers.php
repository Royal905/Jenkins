<?php

namespace FS\Offers\Block;

/**
 * Offers content block
 */
class Offers extends \Magento\Framework\View\Element\Template
{
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context
    ) {
        parent::__construct($context);
    }

    public function _prepareLayout()
    {
        $this->pageConfig->getTitle()->set(__('FS Offers Module'));
        
        return parent::_prepareLayout();
    }
}
