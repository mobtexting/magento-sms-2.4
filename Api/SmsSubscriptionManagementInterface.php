<?php
/**
 * Mobtexting SMS Notifications powered by LINK Mobility
 *
 * Sends transactional SMS notifications through the LINK Mobility messaging
 * service.
 *
 * @package Mobtexting\SMSNotifications\Api
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Api;

/**
 * SMS Subscription Management Service Interface
 *
 * @package Mobtexting\SMSNotifications\Api
 * @api
 */
interface SmsSubscriptionManagementInterface
{
    public function createSubscription(string $smsType, int $customerId): bool;

    /**
     * @param string[] $smsTypes
     * @param string[] $messages
     */
    public function createSubscriptions(array $smsTypes, int $customerId, array $messages = []): int;

    public function removeSubscription(int $subscriptionId, int $customerId): bool;

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
    ): int;
}
