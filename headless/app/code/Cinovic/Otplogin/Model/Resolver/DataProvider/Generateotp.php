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


class Generateotp 
{
   /**
   * @var DataPersistorInterface
   */    
   private $dataPersistor;
   /**
   * @var MailInterface
   */
   private $mail;

   private $formKey;

   private $helper;
   private $emailHelper;


   protected $customerRepository;

   /**
     * @var AccountManagementInterface
     */
   private $customerAccountManagement;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

     /**
     * @var customerCollection
     */
    private $customerCollection;



   /**
   * @param
   */
   public function __construct(
    ConfigInterface $contactsConfig,
    MailInterface $mail,
    DataPersistorInterface $dataPersistor,
    \Magento\Framework\Data\Form\FormKey $formKey,
    \Cinovic\Otplogin\Helper\Data $helper,
    \Cinovic\Otplogin\Helper\Email $emailHelper,
    AccountManagementInterface $customerAccountManagement,
    StoreManagerInterface $storeManager,
    \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
  ) {
    $this->mail = $mail;
    $this->dataPersistor = $dataPersistor;
    $this->formKey = $formKey;
    $this->helper = $helper;
    $this->emailHelper = $emailHelper;
    $this->customerRepository = $customerRepository;
    $this->customerAccountManagement = $customerAccountManagement;
    $this->storeManager = $storeManager;
    
  }

  public function generate($email,$type){



    try {


      $otp_expiry_time = $this->helper->getExpiretime()/60;
      $otp_message = "OTP will expire in ".$otp_expiry_time." minutes. ";

      if($type=='mobile'){

        $mobile_number = $email;


      
        $otp_code = $this->helper->getOtpcode();

        $this->helper->getSendotp($otp_code,$mobile_number);

        $this->helper->setOtpdata($otp_code,$mobile_number);

        $response  = array(
          'status'=>1,
          'message'=>"OTP has been sent at provided number ".$mobile_number,
          'otp'=>'',
          'otp_message'=>$otp_message
        );


      } else {


     


      $websiteId = (int)$this->storeManager->getWebsite()->getId();
      $isEmailAvailable = $this->customerAccountManagement->isEmailAvailable($email, $websiteId);


      if(!$isEmailAvailable){
        $customer = $this->customerRepository->get($email,$websiteId);
        $customerId = (int)$customer->getId();


        $otp = $this->helper->getOtpcode();
        if(!$customer->getFirstname()){
         $customername = 'Customer';
       }else{
         $customername = $customer->getFirstname();
       }

       $email_sent = $this->emailHelper->sendEmail($email,$customername,$otp);

       if($email_sent){
        $message = "Customer OTP has been sent.";			
        $this->helper->setOtpEmaildata($otp, $email,$customername);
        $status = 1;
        $response  = array(
          'status'=>$status,
          'message'=>$message,
          'otp'=>'',
          'otp_message'=>$otp_message 
        );
      }else{
        $response  = array(
          'status'=>0,
          'message'=>'Unable to send OTP. please try later 1.',
          'otp'=>'',
          'otp_message'=>''
        );
      }

    }else{

      $otp = $this->helper->getOtpcode();
      $email_sent = $this->emailHelper->sendEmail($email,'Customer',$otp);
      if($email_sent){
        $message = "New Customer OTP sent.";
        $this->helper->setOtpEmaildata($otp, $email,'Customer');
        $status = 1;
        $response  = array(
          'status'=>$status,
          'message'=>$message,
          'otp'=>'',
          'otp_message'=>$otp_message
        );
      }else{
        $response  = array(
          'status'=>0,
          'message'=>'Unable to send OTP. please try later.',
          'otp'=>'',
          'otp_message'=>''

        );
      }


    }
  }


  }catch (LocalizedException $e) {
    $response  = array(
      'status'=>0,
      'message'=>$e->getMessage(),
      'otp'=>'',
      'otp_message'=>''
    );
  }



  return  $response;
}

}