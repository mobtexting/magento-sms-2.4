<?php
/**
 * Mobtexting SMS Notifications 
 *
 * Sends transactional SMS notifications
 *
 * @package Mobtexting\SMSNotifications\Model\ResourceModel\SmsSubscription
 * 
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Model\ResourceModel\SmsSubscription;

use Mobtexting\SMSNotifications\Model\ResourceModel\SmsSubscription as SmsSubscriptionResourceModel;
use Mobtexting\SMSNotifications\Model\SmsSubscription as SmsSubscriptionModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * SMS Subscription Collection
 *
 * @package Mobtexting\SMSNotifications\Model\ResourceModel\SmsSubscription
 */
class Collection extends AbstractCollection
{
    /**
     * {@inheritdoc}
     */
    protected $_idFieldName = 'sms_subscription_id';

    /**
     * @return string[]
     */
    public function getAllSmsTypes(): array
    {
        $smsTypesSelect = clone $this->getSelect();

        $smsTypesSelect->reset(\Magento\Framework\DB\Select::ORDER);
        $smsTypesSelect->reset(\Magento\Framework\DB\Select::LIMIT_COUNT);
        $smsTypesSelect->reset(\Magento\Framework\DB\Select::LIMIT_OFFSET);
        $smsTypesSelect->reset(\Magento\Framework\DB\Select::COLUMNS);
        $smsTypesSelect->columns('sms_type', 'main_table');

        return $this->getConnection()->fetchCol($smsTypesSelect, $this->_bindParams);
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(SmsSubscriptionModel::class, SmsSubscriptionResourceModel::class);
    }
}
