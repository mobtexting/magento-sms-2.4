<?php
/**
 * Mobtexting SMS Notifications powered by LINK Mobility
 *
 * Sends transactional SMS notifications through the LINK Mobility messaging
 * service.
 *
 * @package Mobtexting\SMSNotifications\Api\Data
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Api\Data;

use \Magento\Framework\Api\ExtensibleDataInterface;

/**
 * SMS Subscription Entity Interface
 *
 * @package Mobtexting\SMSNotifications\Api\Data
 * @api
 */
interface SmsSubscriptionInterface extends ExtensibleDataInterface
{
    const SMS_SUBSCRIPTION_ID = 'sms_subscription_id';
    const CUSTOMER_ID = 'customer_id';
    const SMS_TYPE = 'sms_type';

    /**
     * @param int $id
     * @return \Mobtexting\SMSNotifications\Api\Data\SmsSubscriptionInterface
     */
    public function setId(int $id): SmsSubscriptionInterface;

    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @param int $smsSubscriptionId
     * @return \Mobtexting\SMSNotifications\Api\Data\SmsSubscriptionInterface
     */
    public function setSmsSubscriptionId(int $smsSubscriptionId): SmsSubscriptionInterface;

    /**
     * @return int|null
     */
    public function getSmsSubscriptionId(): ?int;

    /**
     * @param int $customerId
     * @return \Mobtexting\SMSNotifications\Api\Data\SmsSubscriptionInterface
     */
    public function setCustomerId(int $customerId): SmsSubscriptionInterface;

    /**
     * @return int|null
     */
    public function getCustomerId(): ?int;

    /**
     * @param string $smsType
     * @return \Mobtexting\SMSNotifications\Api\Data\SmsSubscriptionInterface
     */
    public function setSmsType(string $smsType): SmsSubscriptionInterface;

    /**
     * @return string|null
     */
    public function getSmsType(): ?string;

    /**
     * @param \Mobtexting\SMSNotifications\Api\Data\SmsSubscriptionExtensionInterface $smsSubscriptionExtension
     * @return \Mobtexting\SMSNotifications\Api\Data\SmsSubscriptionInterface
     *
     * @phpcs:disable Generic.Files.LineLength.TooLong
     */
    public function setExtensionAttributes(SmsSubscriptionExtensionInterface $smsSubscriptionExtension): SmsSubscriptionInterface;

    /**
     * @return \Mobtexting\SMSNotifications\Api\Data\SmsSubscriptionExtensionInterface|null
     */
    public function getExtensionAttributes(): ?SmsSubscriptionExtensionInterface;
}
