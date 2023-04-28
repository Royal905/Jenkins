<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace FS\SocialLoginGraphQl\Model\Resolver;

use Magento\CustomerGraphQl\Model\Customer\CreateCustomerAccount;
use Magento\CustomerGraphQl\Model\Customer\ExtractCustomerData;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Newsletter\Model\Config;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Api\AccountManagementInterface;


/**
 * Create customer account resolver
 */
class CreateCustomer implements ResolverInterface
{
    /**
     * @var ExtractCustomerData
     */
    private $extractCustomerData;

    /**
     * @var CreateCustomerAccount
     */
    private $createCustomerAccount;

    /**
     * @var Config
     */
    private $newsLetterConfig;


      /**
     * @var CreateCustomerAccount
     */
    private $_tokenModelFactory;

     /**
     * @var AccountManagementInterface
     */
    private $customerAccountManagement;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
     /**
     * @var customerRepository
     */
    private $customerRepository;


    /**
     * CreateCustomer constructor.
     *
     * @param ExtractCustomerData $extractCustomerData
     * @param CreateCustomerAccount $createCustomerAccount
     * @param Config $newsLetterConfig
     */
    public function __construct(
        ExtractCustomerData $extractCustomerData,
        CreateCustomerAccount $createCustomerAccount,
        Config $newsLetterConfig,
         AccountManagementInterface $customerAccountManagement,
        StoreManagerInterface $storeManager,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Integration\Model\Oauth\TokenFactory $tokenModelFactory
    ) {
        $this->newsLetterConfig = $newsLetterConfig;
        $this->extractCustomerData = $extractCustomerData;
        $this->createCustomerAccount = $createCustomerAccount;
        $this->_tokenModelFactory = $tokenModelFactory;
        $this->customerAccountManagement = $customerAccountManagement;
        $this->storeManager = $storeManager;
        $this->customerRepository = $customerRepository;
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
       
        if (empty($args['input']) || !is_array($args['input'])) {
            throw new GraphQlInputException(__('"input" value should be specified'));
        }

        if (!$this->newsLetterConfig->isActive(ScopeInterface::SCOPE_STORE)) {
            $args['input']['is_subscribed'] = false;
        }
        if (isset($args['input']['date_of_birth'])) {
            $args['input']['dob'] = $args['input']['date_of_birth'];
        }

        if (isset($args['input']['email'])) {
            $email = $args['input']['email'];
        }




        $websiteId = (int)$this->storeManager->getWebsite()->getId();
        $isEmailAvailable = $this->customerAccountManagement->isEmailAvailable($email, $websiteId);
        
        if(!$isEmailAvailable){
            $customer = $this->customerRepository->get($email);
            $customerId = (int)$customer->getId();
        }else{

            $customer = $this->createCustomerAccount->execute(
                $args['input'],
                $context->getExtensionAttributes()->getStore()
            );
        }
        $data = $this->extractCustomerData->execute($customer);
        if($customer->getId()){
            
          
            $customerToken = $this->_tokenModelFactory->create();
            $customer_token = $customerToken->createCustomerToken($customer->getId())->getToken();

        }else{
            $customer_token = "";
        }
       

        return ['customer' => $data,'token'=>$customer_token];
    }
}
