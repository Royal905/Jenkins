<?php
 
namespace FS\PinCode\Setup;
 
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
 
class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $tableName = $installer->getTable('pin_code');
        //Check for the existence of the table
        if ($installer->getConnection()->isTableExists($tableName) != true) {
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'ID'
                )
                ->addColumn(
                    'city',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'office_name'
                )
                ->addColumn(
                    'pincode',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'pincode'
                )
               
                ->addColumn(
                    'state_name',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'state_name'
                )
                ->addColumn(
                    'status',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'nullable' => false
                    ],
                    'status'
                )
                ->setComment('This is Pin Code information')
                //Set option for magetop_blog table
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}