<?php
/**
 * @author Mohit Patel
 * @copyright Copyright (c) 2021
 * @package Mag_ContactUs
 */

namespace Cinovic\Otplogin\Model\Resolver\DataProvider;

use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Contact\Model\ConfigInterface;
use Magento\Contact\Model\MailInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Api\AccountManagementInterface;

class Validateotp
{
    
   private $dataPersistor;
  
   private $mail;

   private $formKey;

   private $helper;
   private $emailHelper;


  protected $customerRepository;

  protected $token;

  /**
     * @var AccountManagementInterface
     */
    private $customerAccountManagement;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    private $customerFactory;

    private $encryptor;

    private $customerCollectionFactory;


    public function __construct(
        ConfigInterface $contactsConfig,
        MailInterface $mail,
        DataPersistorInterface $dataPersistor,
        \Magento\Framework\Data\Form\FormKey $formKey,
        \Cinovic\Otplogin\Helper\Data $helper,
        \Cinovic\Otplogin\Helper\Email $emailHelper,
         AccountManagementInterface $customerAccountManagement,
        StoreManagerInterface $storeManager,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Cinovic\Otplogin\Model\Token  $token,
        \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerFactory,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor,
            \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory

    ) {
        $this->mail = $mail;
        $this->dataPersistor = $dataPersistor;
        $this->formKey = $formKey;
        $this->helper = $helper;
        $this->token = $token;
        $this->emailHelper = $emailHelper;
        $this->customerRepository = $customerRepository;
        $this->customerAccountManagement = $customerAccountManagement;
        $this->storeManager = $storeManager;
        $this->customerFactory = $customerFactory;
        $this->encryptor = $encryptor;
        $this->customerCollectionFactory = $customerCollectionFactory;
    }

    public function validate($email,$otp,$type){
       
        try {

	       if($type=='mobile'){

          $mobile = $email;
          $is_vaLid = $this->helper->validateOtp($mobile,$otp,'mobile');

          if($is_vaLid==0){
            $response =  array(
                'status'=>0,
                'message'=>'Invalid OTP.',
                'token'=>''
    
             );
            return $response;
          }
          if($is_vaLid==2){
            $response =  array(
                'status'=>0,
                'message'=>'OTP has been expired. please try login again',
                'token'=>''
    
             );
              $this->helper->setUpdateotpstatus($mobile);
            return $response;
          }
          if($is_vaLid==1){

            //$customerId = 2;

            $collection = $this->customerCollectionFactory->create()->addAttributeToSelect('*')
            ->addAttributeToFilter('mobile_number',$mobile)
            ->load();
            
        if($collection->count()){
           $customerId = $collection->getFirstItem()->getId();
           $message = "OTP is validated successfully.";
        }else{

          $email  = 'user.'.$mobile.'@website.com';
          $websiteId = (int)$this->storeManager->getWebsite()->getId();

          $customer = $this->customerFactory->create();
          $customer->setWebsiteId($websiteId);
          $customer->setGroupId(1);
          $customer->setEmail($email);
          $customer->setCustomAttribute('mobile_number',$mobile);
          //$customer->setMobileNumber($mobile);
          $password = $this->helper->getRandomPassword(16);
          $password = $hashedPassword = $this->encryptor->getHash($password, true);

          $this->customerRepository->save($customer, $hashedPassword);
          $customer = $this->customerRepository->get($email);
          $customerId = (int)$customer->getId();
          $message = 'OTP validated successfully . New Customer created.';
          
        }

              $customer_token = $this->token->getToken($customerId);
              $this->helper->setUpdateotpstatus($mobile);
                $response =  array(
                    'status'=>1,
                    'message'=>$message,
                    'token'=>$customer_token
        
                );



          }



         }else{
          $is_vaLid = $this->helper->validateOtp($email,$otp,'email');
          if($is_vaLid==0){
            $response =  array(
                'status'=>0,
                'message'=>'Invalid OTP.',
                'token'=>''
		
             );
            return $response;
          }
          if($is_vaLid==2){
            $response =  array(
                'status'=>0,
                'message'=>'OTP has been expired. please try login again',
                'token'=>''
		
             );
             $this->helper->setEmailUpdateotpstatus($email);
            return $response;
          }
          if($is_vaLid==1){
                $this->helper->setEmailUpdateotpstatus($email);
               
                $websiteId = (int)$this->storeManager->getWebsite()->getId();
                $isEmailAvailable = $this->customerAccountManagement->isEmailAvailable($email, $websiteId);
            
        
                if(!$isEmailAvailable){
                    $customer = $this->customerRepository->get($email);
                    $customerId = (int)$customer->getId();
                    $message = 'OTP validated successfully.';

                }else{
                    //create customer 

                    $customer = $this->customerFactory->create();
                    $customer->setWebsiteId($websiteId);
                     $customer->setWebsiteId($websiteId);
                    $customer->setGroupId(1);
                    $customer->setEmail($email);

                    $password = $this->helper->getRandomPassword(16);
                    $password = $hashedPassword = $this->encryptor->getHash($password, true);

                    $this->customerRepository->save($customer, $hashedPassword);
                    $customer = $this->customerRepository->get($email);
                    $customerId = (int)$customer->getId();
                    $message = 'OTP validated successfully . New Customer created.';
                }
              
                $customer_token = $this->token->getToken($customerId);
                $response =  array(
                    'status'=>1,
                    'message'=>$message,
                    'token'=>$customer_token
		    
                );
          }
        }

            
        }catch (LocalizedException $e) {
                $response =  array(
                    'status'=>0,
                    'message'=>'Error',
                    'token'=>''
		  

                );
        }
       
        return $response;
    }
 
}