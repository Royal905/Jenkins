<?php
namespace FS\BannerGraphQl\Model\Resolver\DataProvider;

use Magento\Store\Model\StoreManagerInterface;


class BannerRecords
{
    private $bannerFactory;
    private $imageHelper;
	private $storeManager;
    private $customerSesion;


    public function __construct(
    \Sparsh\Banner\Model\ResourceModel\Banner\CollectionFactory $bannerFactory,
    \Magento\Customer\Model\Session $customerSesion,
	StoreManagerInterface $storeManager
    ) {
		$this->bannerFactory = $bannerFactory;
		$this->storeManager = $storeManager;
        $this->customerSesion = $customerSesion;


    }

    public function getRecords($customer_group_id=0)
    {
        try {
            $collection = $this->bannerFactory->create();
            $collection->addFieldToFilter("is_active", 1)
            ->addFieldToFilter('store', $this->storeManager->getStore()->getId())
            ->addFieldToFilter('customer', $this->customerSesion->getCustomerGroupId());

             $collection->getSelect()->group('banner_id');

         
            $records = $collection->getData();

        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
        }
		$Recordsdata = array();
		$currentStore = $this->storeManager->getStore();
		$image_media_path = $mediaUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
		foreach($records as $record){
            if($record['banner_image']!=""){
                $record['banner_image'] = $image_media_path.$record['banner_image'];
            }
            if($record['banner_video']!=""){
                $record['banner_video'] = $image_media_path.$record['banner_video'];
            }
			
           
			$Recordsdata[] = $record;
		}
        return $Recordsdata ;
    }
}
