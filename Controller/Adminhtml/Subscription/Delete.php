<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications
 *
 * @package Mobtexting\SMSNotifications\Controller\Adminhtml\Subscription
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Controller\Adminhtml\Subscription;

use Mobtexting\SMSNotifications\Api\SmsSubscriptionManagementInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

/**
 * Delete SMS Subscription Action
 *
 * @package Mobtexting\SMSNotifications\Controller\Adminhtml\Subscription
 */
class Delete extends Action
{
    const ADMIN_RESOURCE = 'Mobtexting_SMSNotifications::manage_sms_subscriptions';

    /**
     * @var \Mobtexting\SMSNotifications\Api\SmsSubscriptionManagementInterface
     */
    private $smsSubscriptionManagement;

    public function __construct(
        Context $context,
        SmsSubscriptionManagementInterface $smsSubscriptionManagement
    ) {
        parent::__construct($context);

        $this->smsSubscriptionManagement = $smsSubscriptionManagement;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        if (
            !$this->_getSession()->hasCustomerData()
            || !array_key_exists('customer_id', $this->_getSession()->getCustomerData())
        ) {
            $this->messageManager->addErrorMessage(__('Could not get customer to unsubscribe from SMS notification.'));

            return $resultRedirect->setPath('customer/index/index');
        }

        $customerId = (int)$this->_getSession()->getCustomerData()['customer_id'];
        $smsSubscriptionId = (int)$this->getRequest()->getParam('sms_subscription_id');

        $resultRedirect->setPath('customer/index/edit', ['id' => $customerId, '_current' => true]);

        if ($this->smsSubscriptionManagement->removeSubscription($smsSubscriptionId, $customerId)) {
            $this->messageManager->addSuccessMessage(
                __('The customer has been unsubscribed from the SMS notification.')
            );
        } else {
            $this->messageManager->addErrorMessage(
                __('Something went wrong while unsubscribing the customer from the SMS notification.')
            );
        }

        return $resultRedirect;
    }
}
