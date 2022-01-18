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

use Mobtexting\SMSNotifications\Api\Data\SmsSubscriptionInterfaceFactory;
use Mobtexting\SMSNotifications\Api\SmsSubscriptionManagementInterface;
use Mobtexting\SMSNotifications\Api\SmsSubscriptionRepositoryInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * SMS Subscription Management Service
 *
 * @package Mobtexting\SMSNotifications\Model
 */
class SmsSubscriptionManagement implements SmsSubscriptionManagementInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    private $messageManager;
    /**
     * @var \Mobtexting\SMSNotifications\Api\Data\SmsSubscriptionInterfaceFactory
     */
    private $smsSubscriptionFactory;
    /**
     * @var \Mobtexting\SMSNotifications\Api\SmsSubscriptionRepositoryInterface
     */
    private $smsSubscriptionRepository;

    public function __construct(
        LoggerInterface $logger,
        ManagerInterface $messageManager,
        SmsSubscriptionInterfaceFactory $smsSubscriptionFactory,
        SmsSubscriptionRepositoryInterface $smsSubscriptionRepository
    ) {
        $this->logger = $logger;
        $this->messageManager = $messageManager;
        $this->smsSubscriptionFactory = $smsSubscriptionFactory;
        $this->smsSubscriptionRepository = $smsSubscriptionRepository;
    }

    public function createSubscription(string $smsType, int $customerId): bool
    {
        /** @var \Mobtexting\SMSNotifications\Api\Data\SmsSubscriptionInterface $smsSubscription */
        $smsSubscription = $this->smsSubscriptionFactory->create();

        $smsSubscription->setCustomerId($customerId);
        $smsSubscription->setSmsType($smsType);

        try {
            $this->smsSubscriptionRepository->save($smsSubscription);
        } catch (CouldNotSaveException $e) {
            $this->logger->critical(
                __('Could not subscribe customer to SMS notification. Error: %1', $e->getMessage()),
                [
                    'customer_id' => $customerId,
                    'sms_type' => $smsType
                ]
            );

            return false;
        }

        return true;
    }

    /**
     * @param string[] $smsTypes
     * @param string[] $messages
     */
    public function createSubscriptions(array $smsTypes, int $customerId, array $messages = []): int
    {
        $createdSubscriptions = 0;

        foreach ($smsTypes as $smsType) {
            if (!$this->createSubscription($smsType, $customerId)) {
                continue;
            }

            ++$createdSubscriptions;
        }

        if (count($messages) === 0) {
            return $createdSubscriptions;
        }

        $remainingSubscriptions = count($smsTypes) - $createdSubscriptions;

        if ($remainingSubscriptions === 1) {
            $this->messageManager->addErrorMessage(__($messages['error']['one']));
        }

        if ($remainingSubscriptions > 1) {
            $this->messageManager->addErrorMessage(__($messages['error']['multiple'], $remainingSubscriptions));
        }

        if ($createdSubscriptions === 1) {
            $this->messageManager->addSuccessMessage(__($messages['success']['one']));
        }

        if ($createdSubscriptions > 1) {
            $this->messageManager->addSuccessMessage(__($messages['success']['multiple'], $createdSubscriptions));
        }

        return $createdSubscriptions;
    }

    public function removeSubscription(int $subscriptionId, int $customerId): bool
    {
        try {
            $this->smsSubscriptionRepository->deleteById($subscriptionId);
        } catch (NoSuchEntityException | CouldNotDeleteException $e) {
            $this->logger->critical(
                __('Could not unsubscribe customer from SMS notification. Error: %1', $e->getMessage()),
                [
                    'sms_subscription_id' => $subscriptionId,
                    'customer_id' => $customerId
                ]
            );

            return false;
        }

        return true;
    }

    /**
     * @param \Mobtexting\SMSNotifications\Model\SmsSubscription[] $subscribedSmsTypes
     * @param string[] $selectedSmsTypes
     * @param string[] $messages
     */
    public function removeSubscriptions(
        array &$subscribedSmsTypes,
        array $selectedSmsTypes,
        int $customerId,
        array $messages = []
    ): int {
        $removedSubscriptions = 0;

        foreach ($subscribedSmsTypes as $key => $subscribedSmsType) {
            if (in_array($subscribedSmsType->getSmsType(), $selectedSmsTypes, true)) {
                continue;
            }

            if (!$this->removeSubscription((int)$subscribedSmsType->getId(), $customerId)) {
                continue;
            }

            ++$removedSubscriptions;

            unset($subscribedSmsTypes[$key]);
        }

        if (count($messages) === 0) {
            return $removedSubscriptions;
        }

        $remainingSubscriptions = array_diff(array_column($subscribedSmsTypes, 'sms_type'), $selectedSmsTypes);
        $remainingSubscriptionCount = count($remainingSubscriptions) - $removedSubscriptions;

        if ($remainingSubscriptionCount === 1) {
            $this->messageManager->addErrorMessage(__($messages['error']['one']));
        }

        if ($remainingSubscriptionCount > 1) {
            $this->messageManager->addErrorMessage(__($messages['error']['multiple'], $remainingSubscriptionCount));
        }

        if ($removedSubscriptions === 1) {
            $this->messageManager->addSuccessMessage(__($messages['success']['one']));
        }

        if ($removedSubscriptions > 1) {
            $this->messageManager->addSuccessMessage(__($messages['success']['multiple'], $removedSubscriptions));
        }

        return $removedSubscriptions;
    }
}
