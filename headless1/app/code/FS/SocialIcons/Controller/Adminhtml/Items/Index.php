<?php

namespace FS\SocialIcons\Controller\Adminhtml\Items;

class Index extends \FS\SocialIcons\Controller\Adminhtml\Items
{
    /**
     * Items list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Fs_SocialIcons::items');
        $resultPage->getConfig()->getTitle()->prepend(__('Offer Items'));
        $resultPage->addBreadcrumb(__('FS'), __('FS'));
        $resultPage->addBreadcrumb(__('Items'), __('Items'));
        return $resultPage;
    }
}