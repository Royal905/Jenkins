<?php 
namespace FS\Offers\Setup; 
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
                $installer->getTable('fs_offers'),
                'position',
                [
                    'type' => Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'default' => 0,
                    'comment' => 'position field'
                ]
            );
        }

         if(version_compare($context->getVersion(), '1.0.2', '<')) {


            $installer->getConnection()->addColumn(
                $installer->getTable('fs_offers'),
                'offer_url',
                [
                    'type' => Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'default' => 0,
                    'comment' => 'offer_url'
                ]
            );
        }
    }
}