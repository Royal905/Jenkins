<?php
namespace FS\ContactGraphQl\Model\Resolver\DataProvider;

use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Contact\Model\ConfigInterface;
use Magento\Contact\Model\MailInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\PageFactory;
use FS\ContactGraphQl\Model\ContactFactory;

use Magento\Framework\Mail\TransportInterfaceFactory;
use Magento\Framework\App\Area;
use Psr\Log\LoggerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\MailException;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;


class Contactus
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
    protected $scopeConfig;
    protected $contactFactory;
    protected $resultPageFactory;
    protected $mailTransportFactory;

    const CUSTOMER_EMAIL_TEMPLATE = 'contact_customer_email_template';

    

    /**
     * @var StateInterface
     */
    private $inlineTranslation;

    /**
     * @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /*
     * @var LoggerInterface
     */
    private $logger;

   
  
    public function __construct(
        ConfigInterface $contactsConfig,
        MailInterface $mail,
        DataPersistorInterface $dataPersistor,
        \Magento\Framework\Data\Form\FormKey $formKey,
        ScopeConfigInterface $scopeConfig,
         StoreManagerInterface $storeManager,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        \FS\ContactGraphQl\Model\ContactFactory $contactFactory,
        TransportInterfaceFactory $mailTransportFactory,
        LoggerInterface $logger
    ) {
        
        $this->mail = $mail;
        $this->mailTransportFactory = $mailTransportFactory;
        $this->dataPersistor = $dataPersistor;
        $this->contactFactory = $contactFactory;

        $this->storeManager = $storeManager;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
        $this->formKey = $formKey;
  
    }

  public function contactUs($fullname,$email,$phone,$numberofemp,$solution,$message,$subject){ 
        
        $thanks_message = [];
        
        try {
                $data = array(
                    'full_name'=>$fullname,
                    'email'=>$email,
                    'phone'=>$phone,
                    'numberofemp'=>$numberofemp,
                    'solution'=>$solution,
                    'message'=>$message,
                    'status'=>0
                    
                );
                
                if ($data) {
                        $model = $this->contactFactory->create();
                        $model->setData($data)->save();
                    }
					
                $emailAdmin = $this->scopeConfig->getValue('contact/email/recipient_email',ScopeInterface::SCOPE_STORE);
              $this->sendEmailToCustomer($fullname,$email,$phone,$numberofemp,$solution,$message);
               $this->sendEmailToAdmin($fullname,$email,$phone,$numberofemp,$solution,$message,$emailAdmin,$subject);
              
              
        
        }catch (LocalizedException $e) { }

        $thanks_message['success_message'] = "Thanks For Contacting Us ";    
        return $thanks_message;
  }
   /**
   * @param array $post Post data from contact form
   * @return void
   * @ function:send mail to admin
   */
   private function sendEmailToAdmin($fullname,$email,$phone,$numberofemp,$solution,$message,$emailAdmin,$subject)
    { 
	 
       $form_data = [];
       $form_data['name']      =   $fullname;
       $form_data['email']     =   $email;
       $form_data['telephone']     = $phone;
       $form_data['numberofemp']  = $numberofemp;
       $form_data['solution']  =   $solution;
       $form_data['comment']   =   $message;
       $form_data['subject']   =   $subject;
       $form_data['form_key']  =   $this->getFormKey();
       
       $this->mail->send(
           $emailAdmin,
           ['data' => new DataObject($form_data)]
       );   
    }
    private function sendEmailToCustomer($fullname,$email,$phone,$numberofemp,$solution,$message)
    {
		
        
        $this->inlineTranslation->suspend();
        $storeId = $this->getStoreId();

        /* email template */
        $template = self::CUSTOMER_EMAIL_TEMPLATE;

	
        $vars = [
            'customer_name' => $fullname,
            'store' => $this->getStore()
        ];

 	$fromEmail = $this->scopeConfig->getValue('trans_email/ident_general/email',ScopeInterface::SCOPE_STORE);
    	$fromName  = $this->scopeConfig->getValue('trans_email/ident_general/name',ScopeInterface::SCOPE_STORE);
	
	$sender = ['email' => $fromEmail, 'name' => $fromName];


       

        $transport = $this->transportBuilder->setTemplateIdentifier($template, ScopeInterface::SCOPE_STORE)
	->setTemplateOptions([
                'area' => Area::AREA_FRONTEND,
                'store' => $this->getStoreId()
	])
	->setTemplateVars(
            $vars
        )->setFrom(
            $sender
        )->addTo(
            $email
        )->getTransport();

        try {
	
            $transport->sendMessage();

        } catch (\Exception $exception) {
            $this->logger->critical($exception->getMessage());
        }
        $this->inlineTranslation->resume();
    }
  public function getFormKey()
    {
        return $this->formKey->getFormKey();
        
    }

     /*
     * get Current store Info
     */
    public function getStore()
    {
        return $this->storeManager->getStore();
    }
    /*
     * get Current store id
     */
    public function getStoreId()
    {
        return $this->storeManager->getStore()->getId();
    }
}