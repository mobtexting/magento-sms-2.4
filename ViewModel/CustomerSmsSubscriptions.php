<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications
 *
 * @package Mobtexting\SMSNotifications\ViewModel
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\ViewModel;

use Mobtexting\SMSNotifications\Api\SmsSubscriptionRepositoryInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Customer SMS Subscriptions View Model
 *
 * @package Mobtexting\SMSNotifications\ViewModel
 */
class CustomerSmsSubscriptions implements ArgumentInterface
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;
    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
    /**
     * @var \Mobtexting\SMSNotifications\Api\SmsSubscriptionRepositoryInterface
     */
    private $smsSubscriptionRepository;
    /**
     * @var string[]
     */
    private $subscribedSmsTypes;

    public function __construct(
        CustomerSession $customerSession,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SmsSubscriptionRepositoryInterface $smsSubscriptionRepository
    ) {
        $this->customerSession = $customerSession;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->smsSubscriptionRepository = $smsSubscriptionRepository;
    }

    /**
     * @param string|int|null $customerId
     */
    public function getSubscribedSmsTypes($customerId = null): array
    {
        if ($this->subscribedSmsTypes !== null) {
            return $this->subscribedSmsTypes;
        }

        $this->subscribedSmsTypes = [];

        if ($customerId === null) {
            $customerId = $this->customerSession->getCustomerId();
        }

        if ($customerId === null) {
            return $this->subscribedSmsTypes;
        }

        $searchCriteria = $this->searchCriteriaBuilder->addFilter('customer_id', $customerId)->create();
        $searchResults = $this->smsSubscriptionRepository->getList($searchCriteria);

        if ($searchResults->getTotalCount() === 0) {
            return $this->subscribedSmsTypes;
        }

        $this->subscribedSmsTypes = array_column($searchResults->getItems(), 'sms_type');

        return $this->subscribedSmsTypes;
    }
}
