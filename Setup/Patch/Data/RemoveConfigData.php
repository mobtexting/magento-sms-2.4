<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications
 *
 * @package Mobtexting\SMSNotifications\Setup\Patch\Data
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

/**
 * SMS Notification Settings Uninstaller
 *
 * @package Mobtexting\SMSNotifications\Setup\Patch\Data
 *
 * @codeCoverageIgnore
 */
class RemoveConfigData implements DataPatchInterface, PatchRevertableInterface
{
    /**
     * @var \Magento\Framework\Setup\ModuleDataSetupInterface
     */
    private $setup;

    public function __construct(ModuleDataSetupInterface $setup)
    {
        $this->setup = $setup;
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function apply(): self
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function revert(): void
    {
        $this->setup->getConnection()->delete(
            $this->setup->getTable('core_config_data'),
            '`path` LIKE \'sms_notifications/%\''
        );
    }
}
