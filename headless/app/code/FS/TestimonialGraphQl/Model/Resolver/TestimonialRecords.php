<?php

namespace FS\TestimonialGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\CustomerGraphQl\Model\Customer\GetCustomer;

class TestimonialRecords implements ResolverInterface
{
    private $bannerRecords;

	 private $getCustomer;

    public function __construct(
        \FS\TestimonialGraphQl\Model\Resolver\DataProvider\TestimonialRecords $bannerRecords,
	 GetCustomer $getCustomer    ) {
        $this->bannerRecords = $bannerRecords;
	$this->getCustomer = $getCustomer;

    }

    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
	
	

        $bannerRecords = $this->bannerRecords->getRecords();
        return $bannerRecords;
    }
}
