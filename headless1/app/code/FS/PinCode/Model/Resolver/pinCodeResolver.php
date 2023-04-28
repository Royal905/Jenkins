<?php
namespace FS\PinCode\Model\Resolver;

use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\CustomerGraphQl\Model\Customer\GetCustomer;

class pinCodeResolver implements ResolverInterface
{
    /**
     * @var \Havells\Dealer\Model\Availability
     */
    protected $PinCodeAvailabilityChecker;

    /**
     * @param \Havells\Dealer\Model\Availability $productAvailabilityChecker
     */
    public function __construct(
       
        \Magento\Customer\Model\Group $customerGroupCollection,
        \FS\PinCode\Model\PinCode $PinCodeAvailabilityChecker
    ) {
        $this->_customerGroupCollection = $customerGroupCollection;
        $this->PinCodeAvailabilityChecker = $PinCodeAvailabilityChecker;
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
        if (empty($args['pinCode'])) {
            throw new GraphQlInputException(__('Specify the pinCode.'));
        }
       
        try {

            
            $pinCodeCollectionFactory=$this->PinCodeAvailabilityChecker;
            $pinCode=$pinCodeCollectionFactory->getCollection()->addFieldToFilter('pincode', ['eq' => $args['pinCode']]);
    
            $pin_codes=$pinCode->getData();

            foreach($pin_codes as $pin_code)
            {
                return ['pinCode' =>$pin_code['pincode'], 'city' =>$pin_code['city'] , 'state_name' =>$pin_code['state_name'],'status' =>$pin_code['status'],'state_code' =>$pin_code['state_code'],'state_id' =>$pin_code['state_id'],'inventory_availability'=>true];
            }

        } catch (\Exception $e) {
            throw new GraphQlInputException(__('something went wrong'.$e->getMessage()));

            
        }
    }
}
