<?php

namespace FS\SocialIconsGraphQl\Model\Resolver\DataProvider;

use Magento\Store\Model\StoreManagerInterface;


class SocialIconsRecords
{
    private $brandCollection;
    private $imageHelper;
	 private $storeManager;


    public function __construct(
    \FS\SocialIcons\Model\SocialIcons $brandCollection,

	StoreManagerInterface $storeManager
    ) {
		$this->brandCollection = $brandCollection;
		$this->storeManager = $storeManager;

    }

    public function getRecords($customer_group_id=0)
    {
        try {
            $collection = $this->brandCollection->getCollection()
            ->setOrder('position','ASC')
            ->addFieldToFilter('status',1);
			
			$records = $collection->getData();

        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
        }
		$Recordsdata = array();
		$currentStore = $this->storeManager->getStore();
		$image_media_path = $mediaUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
		foreach($records as $record){
			$record['image'] = $image_media_path.$record['image'];
			$Recordsdata[] = $record;
		}
        return $Recordsdata ;
    }
}
