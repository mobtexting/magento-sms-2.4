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
use Psr\Log\LoggerInterface;

/**
 * Telephone Prefix Directory Data Importer
 *
 * @package Mobtexting\SMSNotifications\Setup\Patch\Data
 *
 * @codeCoverageIgnore
 */
class ImportTelephonePrefixes implements DataPatchInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;
    /**
     * @var \Magento\Framework\Setup\ModuleDataSetupInterface
     */
    private $setup;

    public function __construct(LoggerInterface $logger, ModuleDataSetupInterface $setup)
    {
        $this->logger = $logger;
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
     * @throws \Exception
     */
    public function apply(): self
    {
        $this->importCountryPhonePrefixes();

        return $this;
    }

    /**
     * @phpcs:disable Generic.Files.LineLength.TooLong
     */
    private function importCountryPhonePrefixes(): void
    {
        $countryPrefixes = file_get_contents(__DIR__ . '/../../_data/country_telephone_prefixes.json');

        if ($countryPrefixes === false) {
            $this->logger->critical(__('Could not get JSON file of country telephone prefixes to import.'));

            return;
        }

        $countryPrefixes = json_decode($countryPrefixes, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->logger->critical(
                __('Could not parse file containing country telephone prefixes as JSON. Error: "%1".', json_last_error_msg())
            );

            return;
        }

        // Strip header row
        unset($countryPrefixes[0]);

        $countryPrefixesTable = $this->setup->getTable('directory_telephone_prefix');

        foreach ($countryPrefixes as $countryPrefix) {
            $this->setup->getConnection()->insert($countryPrefixesTable, $countryPrefix);
        }
    }
}
