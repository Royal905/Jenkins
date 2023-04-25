<?php

namespace FS\OffersGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\CustomerGraphQl\Model\Customer\GetCustomer;

class OfferRecords implements ResolverInterface
{
    private $brandRecords;

	 private $getCustomer;

    public function __construct(
        \FS\OffersGraphQl\Model\Resolver\DataProvider\OfferRecords $brandRecords,
	 GetCustomer $getCustomer    ) {
        $this->brandRecords = $brandRecords;
	$this->getCustomer = $getCustomer;

    }

    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
	
	if (false === $context->getExtensionAttributes()->getIsCustomer()){
            //throw new GraphQlAuthorizationException(__('The current customer isn\'t authorized.try agin with authorization token'));
		$group_id = 0;
        }else{
	
		$customer = $this->getCustomer->execute($context);
       		 $group_id = $customer->getGroupId(); 
	}

	//echo  "Group Id :".$group_id; die;

        $brandRecords = $this->brandRecords->getRecords($group_id);
        return $brandRecords;
    }
}
