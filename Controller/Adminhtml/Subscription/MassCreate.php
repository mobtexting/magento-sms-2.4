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
use Psr\Log\LoggerInterface;

/**
 * Bulk Create SMS Subscription Action
 *
 * @package Mobtexting\SMSNotifications\Controller\Adminhtml\Subscription
 */
class MassCreate extends Action
{
    const ADMIN_RESOURCE = 'Mobtexting_SMSNotifications::manage_sms_subscriptions';

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;
    /**
     * @var \Mobtexting\SMSNotifications\Api\SmsSubscriptionManagementInterface
     */
    private $smsSubscriptionManagement;

    public function __construct(
        Context $context,
        LoggerInterface $logger,
        SmsSubscriptionManagementInterface $smsSubscriptionManagement
    ) {
        parent::__construct($context);

        $this->logger = $logger;
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
            $this->messageManager->addErrorMessage(__('Could not get customer to subscribe to SMS notifications.'));
            $resultRedirect->setPath('customer/index/index');

            return $resultRedirect;
        }

        $customerId = (int)$this->_getSession()->getCustomerData()['customer_id'];
        $selectedSmsTypes = $this->getRequest()->getParam('selected', []);

        $resultRedirect->setPath('customer/index/edit', ['id' => $customerId, '_current' => true]);

        if (count($selectedSmsTypes) === 0) {
            $this->messageManager->addErrorMessage(__('Please select an SMS notification to subscribe the customer to.'));

            return $resultRedirect;
        }

        $messages = [
            'error' => [
                'one' => 'The customer could not be subscribed to 1 SMS notification.',
                'multiple' => 'The customer could not be subscribed to %1 SMS notifications.'
            ],
            'success' => [
                'one' => 'The customer has been subscribed to 1 SMS notification.',
                'multiple' => 'The customer has been subscribed to %1 SMS notifications.'
            ]
        ];

        try {
            $this->smsSubscriptionManagement->createSubscriptions($selectedSmsTypes, $customerId, $messages);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __('Something went wrong while subscribing the customer to the SMS notifications.')
            );
            $this->logger->critical(
                __('Could not subscribe customer to SMS notifications. Error: %1', $e->getMessage()),
                [
                    'customer_id' => $customerId
                ]
            );
        }

        return $resultRedirect;
    }
}
