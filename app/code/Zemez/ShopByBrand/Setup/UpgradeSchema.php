<?php
namespace Zemez\ShopByBrand\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
     public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '0.2.0', '<')) {

            $tableName = $setup->getTable('tm_brand');
            $setup->getConnection()->modifyColumn($tableName, 'website_id', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                ['nullable' => true, 'default' => null]
            ]);
        }
        $setup->endSetup();
    }
}