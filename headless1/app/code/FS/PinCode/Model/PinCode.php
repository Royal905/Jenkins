<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace FS\PinCode\Model;

use Magento\Backend\Model\Auth\Credential\StorageInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Exception\AuthenticationException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\User\Api\Data\UserInterface;
use Magento\User\Model\Spi\NotificationExceptionInterface;
use Magento\User\Model\Spi\NotificatorInterface;
use Magento\Framework\App\DeploymentConfig;

/**
 * Admin user model
 *
 * @api
 * @method string getLogdate()
 * @method \Magento\User\Model\User setLogdate(string $value)
 * @method int getLognum()
 * @method \Magento\User\Model\User setLognum(int $value)
 * @method int getReloadAclFlag()
 * @method \Magento\User\Model\User setReloadAclFlag(int $value)
 * @method string getExtra()
 * @method \Magento\User\Model\User setExtra(string $value)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @api
 * @since 100.0.2
 */
class PinCode extends AbstractModel
{
    
    /**
     * @deprecated New functionality has been added
     * @see \Magento\User\Model\Spi\NotificatorInterface
     */
    const XML_PATH_FORGOT_EMAIL_TEMPLATE = 'admin/emails/forgot_email_template';

    /**
     * @deprecated New functionality has been added
     * @see \Magento\User\Model\Spi\NotificatorInterface
     */
    const XML_PATH_FORGOT_EMAIL_IDENTITY = 'admin/emails/forgot_email_identity';

    /**
     * @deprecated New functionality has been added
     * @see \Magento\User\Model\Spi\NotificatorInterface
     */
    const XML_PATH_USER_NOTIFICATION_TEMPLATE = 'admin/emails/user_notification_template';

    /**
     * Configuration paths for admin user reset password email template
     *
     * @deprecated New functionality has been added
     */
    const XML_PATH_RESET_PASSWORD_TEMPLATE = 'admin/emails/reset_password_template';

    const MESSAGE_ID_PASSWORD_EXPIRED = 'magento_user_password_expired';

    /**
     * Tag to use for user assigned role caching.
     */
    private const CACHE_TAG = 'user_assigned_role';

    /**
     * Model event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'admin_user';

    /**
     * Admin role
     *
     * @var \Magento\Authorization\Model\Role
     */
    protected $_role;

    /**
     * Available resources flag
     *
     * @var bool
     */
    protected $_hasResources = true;

    /**
     * User data
     *
     * @var \Magento\User\Helper\Data
     */
    protected $_userData = null;

    /**
     * Core store config
     *
     * @var \Magento\Backend\App\ConfigInterface
     */
    protected $_config;

    /**
     * Factory for validator composite object
     *
     * @var \Magento\Framework\Validator\DataObjectFactory
     */
    protected $_validatorObject;

    /**
     * Role model factory
     *
     * @var \Magento\Authorization\Model\RoleFactory
     */
    protected $_roleFactory;

    /**
     * @var \Magento\Framework\Encryption\EncryptorInterface
     */
    protected $_encryptor;

    /**
     * @deprecated 101.1.0
     */
    protected $_transportBuilder;

    /**
     * @deprecated 101.1.0
     */
    protected $_storeManager;

    /**
     * @var UserValidationRules
     */
    protected $validationRules;

    /**
     * @var Json
     */
    private $serializer;

    /**
     * @var NotificatorInterface
     */
    private $notificator;

    /**
     * @deprecated 101.1.0
     */
    private $deploymentConfig;
    
    public $_PinCodeResourceModel;

    /**
     * @var array
     */
    protected $_cacheTag = [
        \Magento\Backend\Block\Menu::CACHE_TAGS,
        self::CACHE_TAG,
    ];

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\User\Helper\Data $userData
     * @param \Magento\Backend\App\ConfigInterface $config
     * @param \Magento\Framework\Validator\DataObjectFactory $validatorObjectFactory
     * @param \Magento\Authorization\Model\RoleFactory $roleFactory
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Framework\Encryption\EncryptorInterface $encryptor
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param UserValidationRules $validationRules
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     * @param Json $serializer
     * @param DeploymentConfig|null $deploymentConfig
     * @param NotificatorInterface|null $notificator
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \FS\PinCode\Model\ResourceModel\PinCode $PinCodeResourceModel,
        \Magento\Backend\App\ConfigInterface $config,
        \Magento\Framework\Validator\DataObjectFactory $validatorObjectFactory,
        //\Magento\Authorization\Model\RoleFactory $roleFactory,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        //UserValidationRules $validationRules,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [],
        Json $serializer = null,
        DeploymentConfig $deploymentConfig = null,
        ?NotificatorInterface $notificator = null
    ) {
      
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->_PinCodeResourceModel = $PinCodeResourceModel;
       
       
    }

    /**
     * Initialize user model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\FS\PinCode\Model\ResourceModel\PinCode::class);
    }
    
   

   
   

   
    
   

    
}
