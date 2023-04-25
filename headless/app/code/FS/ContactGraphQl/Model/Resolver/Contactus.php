<?php
namespace FS\ContactGraphQl\Model\Resolver;
//resolver section
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

use FS\ContactGraphQl\Model\Contact;

class Contactus implements ResolverInterface
{
    private $contactusDataProvider;

    /**
     * @param
     */
    public function __construct(
        \FS\ContactGraphQl\Model\Resolver\DataProvider\Contactus $contactusDataProvider
    ) {
		
        $this->contactusDataProvider = $contactusDataProvider;
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
			
        $fullname = $args['input']['fullname'];
        $email = $args['input']['email'];
        $phone = $args['input']['phone'];
	$numberofemp = $args['input']['numberofemp'];
	$solution = $args['input']['solution'];
        $message = $args['input']['message'];
	$status = $args['input']['status'];
	$subject = 'New contact request recieved';


        $success_message = $this->contactusDataProvider->contactUs($fullname,$email,$phone,$numberofemp,$solution,$message,$status,$subject);
			
		return $success_message;
		
    }
}