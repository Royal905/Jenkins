<?php 
namespace FS\PinCode\Model;

use Magento\Catalog\Api\Data\ProductLinkInterfaceFactory as ProductLinkFactory;
use Magento\Framework\App\Filesystem\DirectoryList;

 
class PinCodeManagement {
    

    public function getApiId($pin_code)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        //\Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory;
        $orderCollectionFactory = $objectManager->get('\FS\PinCode\Model\ResourceModel\PinCode\CollectionFactory');
        $collection = $orderCollectionFactory->create();
        $collection->getSelect()->where(
            "(pincode= $pin_code)"
        );
        $data=$collection->getData();
        //$collection->setPageSize(2);
        // foreach ($collection as $product) {
        //     print_r($product->getData());
        // }
         return $data;
    }


}