<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Flags
 */


namespace Amasty\Flags\Setup;

use Amasty\Flags\Model\Flag;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UninstallInterface;

class Uninstall implements UninstallInterface
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(
        Filesystem $filesystem
    ) {
        $this->filesystem = $filesystem;
    }

    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $tablesToDrop = [
            'amasty_flags_order_flag',
            'amasty_flags_flag_column',
            'amasty_flags_flag',
            'amasty_flags_column'
        ];

        foreach ($tablesToDrop as $table) {
            $installer->getConnection()->dropTable(
                $installer->getTable($table)
            );
        }

        $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA)->delete(
            Flag::FLAGS_FOLDER
        );

        $installer->endSetup();
    }
}
