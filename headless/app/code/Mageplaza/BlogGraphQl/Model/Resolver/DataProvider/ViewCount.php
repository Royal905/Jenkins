<?php
namespace Mageplaza\BlogGraphQl\Model\Resolver\DataProvider;

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


use Magento\Framework\Mail\TransportInterfaceFactory;
use Magento\Framework\App\Area;
use Psr\Log\LoggerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\MailException;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;


class ViewCount
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
        \Mageplaza\BlogGraphQl\Model\ViewCountFactory $contactFactory,
        TransportInterfaceFactory $mailTransportFactory,
        LoggerInterface $logger
    ){
        
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

    public function contactUs($post_id)
    { 
        // echo"$post_id"; die;
        $thanks_message = [];    
        try {           

            // $model = $this->contactFactory->get($post_id);
            $model = $this->contactFactory->create();
            
            $model->getCollection()->getData();
            $collection =  $model->getCollection()->addFieldToFilter('post_id',$post_id);
            $new_count = 0;
            if($collection->count()){
                $data = $model->load($collection->getFirstItem()->getTrafficId());
                $new_count = $data->getNumbersView()+1;
                $model->setNumbersView($new_count);
                $model->save();
            }
           
        
        }catch (LocalizedException $e){

        }
        $thanks_message['view_count'] =  $new_count;  
        $thanks_message['success_message'] = "View count updated ";    
        return $thanks_message;
    }

}