<?php

namespace Cinovic\Otplogin\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Email extends AbstractHelper
{
    protected $transportBuilder;
    protected $storeManager;
    protected $inlineTranslation;
    protected $scopeConfig;
     protected $logger;

    const OTP_TEMPALTE_ID = 'cinovic_otplogin_general_email_template';

    public function __construct(
        Context $context,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        StateInterface $state,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $state;
        $this->logger = $logger;
        parent::__construct($context);
    }

    public function sendEmail($toEmail,$customer_name,$otp)
    {
        // this is an example and you can change template id,fromEmail,toEmail,etc as per your need.
        $templateId = self::OTP_TEMPALTE_ID;

        $sender_type = $this->scopeConfig->getValue('cinovic_otplogin/general/identity',ScopeInterface::SCOPE_STORE);

         $fromEmail = $this->scopeConfig->getValue('trans_email/ident_'.$sender_type.'/email',ScopeInterface::SCOPE_STORE);
    $fromName  = $this->scopeConfig->getValue('trans_email/ident_'.$sender_type.'/name',ScopeInterface::SCOPE_STORE);

        //echo "$templateId , $fromEmail ,  $toEmail , $customer_name , $otp"; die;
        try {
            // template variables here
		$minutes = $this->scopeConfig->getValue('cinovic_otplogin/general/expire_time',ScopeInterface::SCOPE_STORE)/60;
		$expire_time_in_minutes = $minutes. " minutes";

            $templateVars = [
                'expire_time' => $expire_time_in_minutes,
                'customer_name' => $customer_name,
                'otp'=>$otp
            ];

            $storeId = $this->storeManager->getStore()->getId();

            $from = ['email' => $fromEmail, 'name' => $fromName];
            $this->inlineTranslation->suspend();

            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
            $templateOptions = [
                'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                'store' => $storeId
            ];

            $transport = $this->transportBuilder->setTemplateIdentifier($templateId, $storeScope)
                ->setTemplateOptions($templateOptions)
                ->setTemplateVars($templateVars)
                ->setFrom($from)
                ->addTo($toEmail)
                ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->_logger->info($e->getMessage());
            return false;
        }
         return true;
    }
}