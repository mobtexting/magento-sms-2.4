<?php
/**
 * Mobtexting SMS Notifications 
 *
 * Sends transactional SMS notifications
 *
 * @package Mobtexting\SMSNotifications\Model
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Model;

use Mobtexting\SMSNotifications\Api\ConfigInterface;
use Mobtexting\SMSNotifications\Api\SmsSubscriptionRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;
use Magento\Store\Api\StoreRepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * SMS Sender
 *
 * @package Mobtexting\SMSNotifications\Model
 */
abstract class SmsSender
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;
    /**
     * @var \Magento\Store\Api\StoreRepositoryInterface
     */
    protected $storeRepository;
    /**
     * @var \Mobtexting\SMSNotifications\Api\ConfigInterface
     */
    protected $config;
    /**
     * @var \Mobtexting\SMSNotifications\Model\MessageService
     */
    protected $messageService;
    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    private $customerRepository;
    /**
     * @var \Mobtexting\SMSNotifications\Api\SmsSubscriptionRepositoryInterface
     */
    private $subscriptionRepository;

    public function __construct(
        LoggerInterface $logger,
        StoreRepositoryInterface $storeRepository,
        CustomerRepositoryInterface $customerRepository,
        ConfigInterface $config,
        SmsSubscriptionRepositoryInterface $subscriptionRepository,
        MessageService $messageService
    ) {
        $this->logger = $logger;
        $this->storeRepository = $storeRepository;
        $this->customerRepository = $customerRepository;
        $this->config = $config;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->messageService = $messageService;
    }

    abstract public function send(AbstractModel $entity): bool;

    protected function getWebsiteIdByStoreId(?int $storeId): ?int
    {
        try {
            $websiteId = (int)$this->storeRepository->getById($storeId)->getWebsiteId();
        } catch (NoSuchEntityException $e) {
            $websiteId = null;
        }

        return $websiteId;
    }

    protected function isModuleEnabled(?int $websiteId = null): bool
    {
        return $this->config->isEnabled($websiteId);
    }

    protected function getCustomerById(int $customerId): ?CustomerInterface
    {
        try {
            $customer = $this->customerRepository->getById($customerId);
        } catch (NoSuchEntityException | LocalizedException $e) {
            $this->logger->critical(
                __('Could not get customer by ID. Error: %1', $e->getMessage()),
                ['customer_id' => $customerId]
            );

            return null;
        }

        return $customer;
    }

    protected function getCustomerMobilePhoneNumber(CustomerInterface $customer): ?string
    {
        $mobilePhonePrefixAttribute = $customer->getCustomAttribute('sms_mobile_phone_prefix');
        $mobilePhoneNumberAttribute = $customer->getCustomAttribute('sms_mobile_phone_number');

        if (
            $mobilePhonePrefixAttribute === null
            || $mobilePhoneNumberAttribute === null
            || empty($mobilePhonePrefixAttribute->getValue())
            || empty($mobilePhoneNumberAttribute->getValue())
        ) {
            return null;
        }

        $mobilePhonePrefix = '+' . substr($mobilePhonePrefixAttribute->getValue(), 3);
        $mobilePhoneNumber = preg_replace('/\D+/', '', $mobilePhoneNumberAttribute->getValue());

        return $mobilePhonePrefix . $mobilePhoneNumber;
    }

    protected function getCustomerSmsSubscriptions(int $customerId): array
    {
        $searchResults = $this->subscriptionRepository->getListByCustomerId($customerId);

        if ($searchResults->getTotalCount() === 0) {
            return [];
        }

        return array_column($searchResults->getItems(), 'sms_type');
    }
}
