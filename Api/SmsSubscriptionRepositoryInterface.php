<?php
/**
 * Mobtexting SMS Notifications powered by LINK Mobility
 *
 * Sends transactional SMS notifications through the LINK Mobility messaging
 * service.
 *
 * @package Mobtexting\SMSNotifications\Api
 *
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Api;

use Mobtexting\SMSNotifications\Api\Data\SmsSubscriptionInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;

/**
 * SMS Subscription Repository Interface
 *
 * @package Mobtexting\SMSNotifications\Api
 * @api
 */
interface SmsSubscriptionRepositoryInterface
{
    /**
     * @param int $id
     * @return \Mobtexting\SMSNotifications\Api\Data\SmsSubscriptionInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $id): SmsSubscriptionInterface;

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;

    /**
     * @param int $customerId
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function getListByCustomerId(int $customerId): SearchResultsInterface;

    /**
     * @param \Mobtexting\SMSNotifications\Api\Data\SmsSubscriptionInterface $smsSubscription
     * @return \Mobtexting\SMSNotifications\Api\Data\SmsSubscriptionInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(SmsSubscriptionInterface $smsSubscription): SmsSubscriptionInterface;

    /**
     * @param \Mobtexting\SMSNotifications\Api\Data\SmsSubscriptionInterface $smsSubscription
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(SmsSubscriptionInterface $smsSubscription): bool;

    /**
     * @param int $id
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById(int $id): bool;
}
