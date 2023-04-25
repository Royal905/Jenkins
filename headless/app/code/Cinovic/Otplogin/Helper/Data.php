<?php

/**
 * Cinovic Technologies LLP.
 *
 * @category  Cinovic
 * @package   Cinovic_Otplogin
 * @author    Cinovic Technologies LLP
 * @copyright Copyright (c) Cinovic Technologies LLP (https://cinovic.com)
 * @license   https://store.cinovic.com/license.html
 */


namespace Cinovic\Otplogin\Helper;

use Twilio\Rest\Client;

/**
 * Class Data
 * @package Cinovic\Otplogin\Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    const OTP_TYPE = 'cinovic_otplogin/general/otp_type';
    const OTP_LENGTH = 'cinovic_otplogin/general/otp_length';
    const MOBILE_NUMBER = 'cinovic_otplogin/api_configuration/mobile_number';
    const SELLER_ID = 'cinovic_otplogin/api_configuration/sender_id';
    const AUTHORIZATION_KEY = 'cinovic_otplogin/api_configuration/authorization_key';
    const EXPIRE_TIME = 'cinovic_otplogin/general/expire_time';

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    public $storeManager;

     public $companyCollection;

    /**
     * Data constructor
     * @param \Magento\Framework\App\Config\ScopeConfigInterface   $scopeConfig    [description]
     * @param \Magento\Framework\App\Helper\Context                $context        [description]
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager             [description]
     * @param \Cinovic\Otplogin\Model\OtpFactory                   $otpFactory     [description]
     * @param \Magento\Customer\Model\ResourceModel\Customer\Collection $collection    [description]    
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Cinovic\Otplogin\Model\OtpFactory $otpFactory,
        \Magento\Customer\Model\ResourceModel\Customer\Collection $collection
      //  \Webkul\Grid\Model\ResourceModel\Grid\Collection $companyCollection
    ) {
        $this->otpFactory = $otpFactory;
        $this->collection = $collection;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
       // $this->companyCollection = $companyCollection;
        return parent::__construct($context);
    }

     /**
     * @param  String $path
     * @return string
     */
    public function getConfigvalue($path)
    {
        return $this->scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getOtptype()
    {
        return $this->getConfigvalue(self::OTP_TYPE);
    }

    /**
     * @return string
     */
    public function getOtplength()
    {
        return $this->getConfigvalue(self::OTP_LENGTH);
    }

    /**
     * @return string
     */
    public function getSmsmobile()
    {
        return $this->getConfigvalue(self::MOBILE_NUMBER);
    }

    /**
     * @return string
     */
    public function getSellerId()
    {
        return $this->getConfigvalue(self::SELLER_ID);
    }

    /**
     * @return string
     */
    public function getAUthkey()
    {
        return $this->getConfigvalue(self::AUTHORIZATION_KEY);
    }

    /**
     * @return string
     */
    public function getExpiretime()
    {
        return $this->getConfigvalue(self::EXPIRE_TIME);
    }

    /**
     * @return string
     */
    public function getOtpcode()
    {
        $otp_type = $this->getOtptype();
        $otp_length = $this->getOtplength();

        if (empty($otp_length)) {
            $otp_length = 4;
        }
        if ($otp_type == "number") {
            $str_result = '0123456789';
            $otp_code =  substr(str_shuffle($str_result), 0, $otp_length);
        } elseif ($otp_type == "alphabets") {
            $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            $otp_code =  substr(str_shuffle($str_result), 0, $otp_length);
        } elseif ($otp_type == "alphanumeric") {
            $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            $otp_code =  substr(str_shuffle($str_result), 0, $otp_length);
        } else {
            $otp_code = mt_rand(10000, 99999);
        }
        return $otp_code;
    }

    /**
     * Send Sms
     */
    public function getSendotp($otp_code, $mobile_number)
    {
        $number = $this->getSmsmobile();
        $sid  =     $this->getSellerId();
        $token  = $this->getAUthkey();

        $twilio = new Client($sid, $token);

        $storeName = $this->storeManager->getStore()->getName();

        $message_body = "Your OTP for login at ".$storeName." is ".$otp_code;

        $twilio->messages
            ->create(
                $mobile_number, // to
                ["from" => "+" . $number, "body" => $message_body]
            );
    }

    /**
     * Save Otp
     */
    public function setOtpdata($otp, $mobile_number)
    {
        $question = $this->otpFactory->create();
        $question->setOtp($otp);
        $question->setCustomerMobile($mobile_number);
        $question->setStatus('1');
        $question->save();
    }


     /**
     * Save Otp
     */
    public function setOtpEmaildata($otp, $email,$name)
    {
	$this->setEmailUpdateotpstatus($email);

        $question = $this->otpFactory->create();
        $question->setOtp($otp);
        $question->setCustomerEmail($email);
        $question->setName($name);
        $question->setStatus('1');
        
        $question->save();

	

    }

    /**
     * Update Otp
     */
    public function setUpdateotpstatus($mobile_number)
    {
        $customerstatus = $this->otpFactory->create()->getCollection()->addFieldToFilter('customer_mobile', $mobile_number)->getData();
        if (!empty($customerstatus)) {
            foreach ($customerstatus as $data) {
                $customerstatus1 = $this->otpFactory->create()->load($data['entity_id']);
                $customerstatus1->setStatus('0');
                $customerstatus1->save();
            }
        }
    }

       /*
       * Update Otp
     */
    public function setEmailUpdateotpstatus($customer_email)
    {
        $customerstatus = $this->otpFactory->create()->getCollection()
        ->addFieldToFilter('customer_email', $customer_email)
        ->addFieldToFilter('status', 1)
        ->getData();
        if (!empty($customerstatus)) {
            foreach ($customerstatus as $data) {
                $customerstatus1 = $this->otpFactory->create()->load($data['entity_id']);
                $customerstatus1->setStatus('0');
                $customerstatus1->save();
            }
        }
    }

    

    public function validateOtp($customer_email,$otp,$type)
    {
        if($type=='mobile'){
              $otps = $this->otpFactory->create()->getCollection()
            ->addFieldToFilter('customer_mobile', $customer_email)
             ->addFieldToFilter('otp', $otp)
             ->addFieldToFilter('status', 1)
             ->setOrder('entity_id','DESC');
        }
        if($type=='email'){
            $otps = $this->otpFactory->create()->getCollection()
            ->addFieldToFilter('customer_email', $customer_email)
             ->addFieldToFilter('otp', $otp)
             ->addFieldToFilter('status', 1)
             ->setOrder('entity_id','DESC');
        }
       
//var_dump($otps->count()); die;

       if($otps->count()){
            $expire_duration = (int)$this->getExpiretime();
            $created_time = $otps->getFirstItem()->getCreatedAt();
            $expiry_time = date('Y-m-d H:i:s', strtotime($created_time. " +$expire_duration seconds"));


            $current_timestamp = new \DateTime(date('Y-m-d H:i:s'));
            $expiry_timestamp = new \DateTime($expiry_time);

            
          
            if($current_timestamp > $expiry_timestamp){
                //OTP expired
                return 2;
            }else{
                return 1;
            }
            
       }
       return 0;
    }

     public function validateEmailForCompany($email){

        $domain_name = substr(strrchr($email, "@"), 1);

        $companies = $this->companyCollection
            ->addFieldToFilter('allowed_email_domains',array("finset"=>array($domain_name)))->addFieldToFilter('is_active',1);

        if( $companies->count()){
            return true;
        }

        return false;
     }

     public function getCustomerGroupId($email){

        $domain_name = substr(strrchr($email, "@"), 1);

        $companies = $this->companyCollection
            ->addFieldToFilter('allowed_email_domains',array("finset"=>array($domain_name)))->addFieldToFilter('is_active',1);

        if( $companies->count()){
            return $companies->getFirstItem()->getCustomerGroupId();
        }
        return false;
     }


     public function getRandomPassword($length_of_string){
 
        // String of all alphanumeric character
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
     
        // Shuffle the $str_result and returns substring
        // of specified length
        return substr(str_shuffle($str_result),$length_of_string);
    }
}
