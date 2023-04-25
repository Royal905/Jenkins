<?php
/**
 * @author FS
 * @copyright Copyright (c) 2021
 * @package Cinovic_Otplogin
 */

namespace Cinovic\Otplogin\Model\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;


class Validateotp implements ResolverInterface
{
    private $validateotpDataProvider;

    /**
     * @param
     */
    public function __construct(
        \Cinovic\Otplogin\Model\Resolver\DataProvider\Validateotp $validateotpDataProvider
    ) {
        $this->validateotpDataProvider = $validateotpDataProvider;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
      

        $otp = $args['input']['otp'];

         if(isset($args['input']['email']) && $args['input']['email']!=""){
            $email = $args['input']['email'];
            $data = $this->validateotpDataProvider->validate($email,$otp,'email');
        }
        if(isset($args['input']['mobile']) &&  $args['input']['mobile']!=""){
            $mobile = $args['input']['mobile'];
            $data = $this->validateotpDataProvider->validate($mobile,$otp,'mobile');
        }
        

        return $data;
    }
}