<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace FS\Bestseller\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\GraphQl\Model\Query\ContextInterface;
use Magento\Catalog\Api\ScopedProductTierPriceManagementInterface;
use Magento\CustomerGraphQl\Model\Customer\GetCustomer;
use Magento\Framework\Locale\CurrencyInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Sales\Model\ResourceModel\Report\Bestsellers\CollectionFactory as BestSellersCollectionFactory;

/**
 * @inheritdoc
 */

class BestSeller1 implements ResolverInterface
{
    

    
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
       \Magento\Store\Model\StoreManagerInterface $storeManager,
 
       \Magento\Directory\Model\Currency $currency, 
       CurrencyInterface $localeCurrency,
       CollectionFactory $productCollectionFactory,
       BestSellersCollectionFactory $bestSellersCollectionFactory,
       \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository,
       \Magento\Catalog\Model\CategoryRepository $catalogRepository
    ){
        $this->_scopeConfig = $scopeConfig;
        $this->_bestSellersCollectionFactory = $bestSellersCollectionFactory;
        $this->_productCollectionFactory= $productCollectionFactory;
        $this->_stockItemRepository = $stockItemRepository;
      
       $this->storeManager = $storeManager;
  
  
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
       
                        
        $productIds = [];
        $bestSellers = $this->_bestSellersCollectionFactory->create()
            ->setPeriod('day');

        $qtys_ordered  = [];
        foreach ($bestSellers as $product) {


            $productIds[] = $product->getProductId();
            $qtys_ordered[$product->getProductId()] = $product->getQtyOrdered();

        }

        $productIdSorted = arsort($qtys_ordered);


        $productIdsNew = array_keys($qtys_ordered);

       
        $collection = $this->_productCollectionFactory->create()->addIdFilter($productIds);
        $collection->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addAttributeToSelect('*')
            ->addStoreFilter($this->getStoreId())
            ->setPageSize(count($productIds));
        $i= 0;
	$products=[];

    $objectManager =\Magento\Framework\App\ObjectManager::getInstance();
$helperImport = $objectManager->get('\Magento\Catalog\Helper\Image');



	 foreach ($collection as $product) {
                $products['data'][$i]['name'] = $product->getName();
               
              
           $imageUrl = $helperImport->init($product, 'product_page_image_small')
                ->setImageFile($product->getSmallImage()) // image,small_image,thumbnail
                ->resize(250)
                ->getUrl();
                $products['data'][$i]['sku'] = $product->getSku();
               $products['data'][$i]['price']=$product->getPrice();
		        $products['data'][$i]['final_price']=$product->getFinalPrice();
             	$products['data'][$i]['image']=$imageUrl;
                $products['data'][$i]['short_desc']=$product->getShortDescription();
                $products['data'][$i]['urlkey'] = $product->getUrlKey();
                $products['data'][$i]['uid'] = base64_encode($product->getId());
                $products['data'][$i]['stock_status'] = $this->getStockItem($product->getId());
                $products['data'][$i]['type_id'] = $product->getTypeId();
                $products['data'][$i]['qtys_ordered'] = $qtys_ordered[$product->getId()];
        
                $i++;
            }
           
            $qtys = array();
    #iterating over the arr
    foreach ($products['data'] as $key => $val){
      #storing the key of the names array as the Name key of the arr
        $qtys[$key] = $val['qtys_ordered'];
          
    }

    array_multisort($qtys, SORT_DESC, $products['data']);


            return $products;        
    }
    public function getStoreId(){
        return $this->storeManager->getStore()->getId();
    }
    public function getStockItem($productId)
    {
        $stock_status='';
        $productInventory= $this->_stockItemRepository->get($productId);
        if($productInventory->getIsInStock()==1){
            $stock_status="IN_STOCK";
        }else{
            $stock_status="OUT_OF_STOCK";
        }
        return $stock_status;
    }
   
}
