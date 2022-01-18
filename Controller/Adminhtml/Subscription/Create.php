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
 * Create SMS Subscription Action
 *
 * @package Mobtexting\SMSNotifications\Controller\Adminhtml\Subscription
 */
class Create extends Action
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
     *
     * @phpcs:disable Generic.Files.LineLength.TooLong
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        if (
            !$this->_getSession()->hasCustomerData()
            || !array_key_exists('customer_id', $this->_getSession()->getCustomerData())
        ) {
            $this->messageManager->addErrorMessage(__('Could not get customer to subscribe to SMS notification.'));
            $resultRedirect->setPath('customer/index/index');

            return $resultRedirect;
        }

        $customerId = (int)$this->_getSession()->getCustomerData()['customer_id'];
        $smsType = $this->getRequest()->getParam('sms_type');

        $resultRedirect->setPath('customer/index/edit', ['id' => $customerId, '_current' => true]);

        if (empty($smsType)) {
            $this->messageManager->addErrorMessage(__('Please select an SMS notification to subscribe the customer to.'));

            return $resultRedirect;
        }

        if ($this->smsSubscriptionManagement->createSubscription($smsType, $customerId)) {
            $this->messageManager->addSuccessMessage(
                __('The customer has been subscribed to the SMS notification.')
            );
        } else {
            $this->messageManager->addErrorMessage(
                __('Something went wrong while subscribing the customer to the SMS notification.')
            );
        }

        return $resultRedirect;
    }
}
