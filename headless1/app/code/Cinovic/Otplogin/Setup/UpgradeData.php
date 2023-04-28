<?php 
namespace Cinovic\Otplogin\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

class UpgradeData implements UpgradeDataInterface
{

    public function __construct(EavSetupFactory $eavSetupFactory)
    {
       $this->eavSetupFactory = $eavSetupFactory;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    { 
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        if (version_compare($context->getVersion(), '1.0.1', '<')) {

            $entityType = $eavSetup->getEntityTypeId('customer');

            $eavSetup->updateAttribute($entityType, 'firstname', 'is_required',0);
            $eavSetup->updateAttribute($entityType, 'lastname', 'is_required',0);
        }
    }
 }