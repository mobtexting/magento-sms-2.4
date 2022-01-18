<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications
 * 
 * @package Mobtexting\SMSNotifications\Model\Customer\Attribute\Source
 *
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Model\Customer\Attribute\Source;

use Mobtexting\SMSNotifications\Model\ResourceModel\TelephonePrefix\CollectionFactory as TelephonePrefixCollectionFactory;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Data\Collection;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Telephone Prefix Source Model
 *
 * @package Mobtexting\SMSNotifications\Model\Customer\Attribute\Source
 */
class TelephonePrefix extends AbstractSource
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var \Mobtexting\SMSNotifications\Model\ResourceModel\TelephonePrefix\CollectionFactory
     */
    private $prefixCollectionFactory;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        TelephonePrefixCollectionFactory $prefixCollectionFactory
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->prefixCollectionFactory = $prefixCollectionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllOptions(bool $withEmpty = true): array
    {
        $prefixCollection = $this->prefixCollectionFactory->create()
            ->setOrder('country_name', Collection::SORT_ORDER_ASC);
        $allowedCountries = $this->getAllowedCountries();

        if (count($allowedCountries) > 0) {
            $prefixCollection->addFieldToFilter('country_code', ['in' => $allowedCountries]);
        }

        $prefixes = $prefixCollection->load()
            ->getItems();
        $options = [];

        /** @var \Mobtexting\SMSNotifications\Model\TelephonePrefix $prefix */
        foreach ($prefixes as $prefix) {
            $options[] = [
                'value' => $prefix->getCountryCode() . '_' . $prefix->getPrefix(),
                'label' => $prefix->getCountryName() . ' (+' . $prefix->getPrefix() . ')'
            ];
        }

        if ($withEmpty) {
            array_unshift($options, ['value' => '', 'label' => ' ']);
        }

        return $options;
    }

    private function getAllowedCountries(): array
    {
        try {
            $scopeCode = $this->storeManager->getStore()->getWebsiteId();
            $scopeType = ScopeInterface::SCOPE_WEBSITE;
        } catch (NoSuchEntityException $e) {
            $scopeCode = null;
            $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT;
        }

        $allowedCountries = $this->scopeConfig->getValue('general/country/allow', $scopeType, $scopeCode);

        if ($allowedCountries === null) {
            return [];
        }

        return explode(',', $allowedCountries);
    }
}
