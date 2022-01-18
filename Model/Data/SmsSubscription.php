<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications 
 *
 * @package Mobtexting\SMSNotifications\Model\Data
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Model\Data;

use Mobtexting\SMSNotifications\Api\Data\SmsSubscriptionExtensionInterface;
use Mobtexting\SMSNotifications\Api\Data\SmsSubscriptionInterface;
use Magento\Framework\Api\AbstractExtensibleObject;

/**
 * SMS Subscription Entity
 *
 * @package Mobtexting\SMSNotifications\Model\Data
 */
class SmsSubscription extends AbstractExtensibleObject implements SmsSubscriptionInterface
{
    /**
     * @param int $id
     * @return \Mobtexting\SMSNotifications\Api\Data\SmsSubscriptionInterface
     */
    public function setId(int $id): SmsSubscriptionInterface
    {
        return $this->setSmsSubscriptionId($id);
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->getSmsSubscriptionId();
    }

    /**
     * @param int $smsSubscriptionId
     * @return \Mobtexting\SMSNotifications\Api\Data\SmsSubscriptionInterface
     */
    public function setSmsSubscriptionId(int $smsSubscriptionId): SmsSubscriptionInterface
    {
        return $this->setData(self::SMS_SUBSCRIPTION_ID, $smsSubscriptionId);
    }

    /**
     * @return int|null
     */
    public function getSmsSubscriptionId(): ?int
    {
        return $this->_get(self::SMS_SUBSCRIPTION_ID);
    }

    /**
     * @param int $customerId
     * @return \Mobtexting\SMSNotifications\Api\Data\SmsSubscriptionInterface
     */
    public function setCustomerId(int $customerId): SmsSubscriptionInterface
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * @return int|null
     */
    public function getCustomerId(): ?int
    {
        return $this->_get(self::CUSTOMER_ID);
    }

    /**
     * @param string $smsType
     * @return \Mobtexting\SMSNotifications\Api\Data\SmsSubscriptionInterface
     */
    public function setSmsType(string $smsType): SmsSubscriptionInterface
    {
        return $this->setData(self::SMS_TYPE, $smsType);
    }

    /**
     * @return string|null
     */
    public function getSmsType(): ?string
    {
        return $this->_get(self::SMS_TYPE);
    }

    /**
     * @param \Mobtexting\SMSNotifications\Api\Data\SmsSubscriptionExtensionInterface $smsSubscriptionExtension
     * @return \Mobtexting\SMSNotifications\Api\Data\SmsSubscriptionInterface
     *
     * @phpcs:disable Generic.Files.LineLength.TooLong
     */
    public function setExtensionAttributes(SmsSubscriptionExtensionInterface $smsSubscriptionExtension): SmsSubscriptionInterface
    {
        return $this->_setExtensionAttributes($smsSubscriptionExtension);
    }

    /**
     * @return \Mobtexting\SMSNotifications\Api\Data\SmsSubscriptionExtensionInterface|null
     */
    public function getExtensionAttributes(): ?SmsSubscriptionExtensionInterface
    {
        return $this->_getExtensionAttributes();
    }
}
