<?php 
namespace FS\BannerGraphQl\Setup; 
use Magento\Framework\Setup\UpgradeSchemaInterface; 
use Magento\Framework\Setup\ModuleContextInterface; 
use Magento\Framework\Setup\SchemaSetupInterface; 
use Magento\Framework\DB\Ddl\Table; 

class UpgradeSchema implements UpgradeSchemaInterface { 
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context) { 
        
        $installer = $setup;

        $installer->startSetup();

         if(version_compare($context->getVersion(), '1.0.1', '<')) {


            $installer->getConnection()->addColumn(
                $installer->getTable('sparsh_banner'),
                'banner_image_mobile',
                [
                    'type' => Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'default' => 0,
                    'comment' => 'banner_image mobile'
                ]
            );
        }
    }
}