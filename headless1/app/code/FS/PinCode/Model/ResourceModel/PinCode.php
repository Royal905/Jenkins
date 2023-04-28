<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace FS\PinCode\Model\ResourceModel;

use Magento\Authorization\Model\Acl\Role\Group as RoleGroup;
use Magento\Authorization\Model\Acl\Role\User as RoleUser;
use Magento\Authorization\Model\UserContextInterface;
use Magento\Framework\Acl\Data\CacheInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\User\Model\Backend\Config\ObserverConfig;
use Magento\User\Model\User as ModelUser;

/**
 * ACL user resource
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @api
 * @since 100.0.2
 */
class PinCode extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Role model
     *
     * @var \Magento\Authorization\Model\RoleFactory
     */
    protected $_roleFactory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    protected $dateTime;

    /**
     * @var CacheInterface
     */
    private $aclDataCache;

    /**
     * @var ObserverConfig|null
     */
    private $observerConfig;

    /**
     * Construct
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
   
     * @param \Magento\Framework\Stdlib\DateTime $dateTime
     * @param string $connectionName
     * @param CacheInterface $aclDataCache
     * @param ObserverConfig|null $observerConfig
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
      
        \Magento\Framework\Stdlib\DateTime $dateTime,
        $connectionName = null,
        CacheInterface $aclDataCache = null,
        ObserverConfig $observerConfig = null
    ) {
        parent::__construct($context, $connectionName);
        //$this->_roleFactory = $roleFactory;
        $this->dateTime = $dateTime;
        //$this->aclDataCache = $aclDataCache ?: ObjectManager::getInstance()->get(CacheInterface::class);
        $this->observerConfig = $observerConfig ?: ObjectManager::getInstance()->get(ObserverConfig::class);
    }

    /**
     * Define main table
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('pin_code', 'id');
    }

    
    
    
    

   
  
   
    

    
 
}
