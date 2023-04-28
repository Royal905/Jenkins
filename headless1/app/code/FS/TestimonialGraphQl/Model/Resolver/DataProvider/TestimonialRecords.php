<?php
namespace FS\TestimonialGraphQl\Model\Resolver\DataProvider;

use Magento\Catalog\Helper\Image as HelperImage;
use Magento\Store\Model\StoreManagerInterface;


class TestimonialRecords
{
    private $bannerFactory;
    private $imageHelper;
	 private $storeManager;


    public function __construct(
        \Swissup\Testimonials\Model\ResourceModel\Data\CollectionFactory $bannerFactory,
	StoreManagerInterface $storeManager

    ){
         $this->bannerFactory = $bannerFactory;
	$this->storeManager = $storeManager;

    }

    public function getRecords($customer_group_id=0)
    {
        try {
            $collection = $this->bannerFactory->create();
             $collection->addFieldToFilter("status", 2);

             
            $records = $collection->getData();

            //echo '<pre>'; print_r($records); 

        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
        }
	$Recordsdata = array();
 	$currentStore = $this->storeManager->getStore();
 	$image_media_path = $mediaUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'testimonials/image/';
	foreach($records as $record){
		$record['image'] = $image_media_path.$record['image'];
		$Recordsdata[] = $record;
	}
        return $Recordsdata ;
    }
}
