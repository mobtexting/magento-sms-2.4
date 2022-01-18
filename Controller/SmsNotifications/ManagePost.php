<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications
 *
 * @package Mobtexting\SMSNotifications\Controller\SmsNotifications
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Controller\SmsNotifications;

use Mobtexting\SMSNotifications\Api\MobileTelephoneNumberManagementInterface;
use Mobtexting\SMSNotifications\Api\SmsSubscriptionManagementInterface;
use Mobtexting\SMSNotifications\Api\SmsSubscriptionRepositoryInterface;
use Mobtexting\SMSNotifications\Model\SmsSender;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Psr\Log\LoggerInterface;

/**
 * Manage SMS Subscriptions POST Action Controller
 *
 * @package Mobtexting\SMSNotifications\Controller\SmsNotifications
 */
class ManagePost extends Action implements ActionInterface, CsrfAwareActionInterface
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;
    /**
     * @var \Mobtexting\SMSNotifications\Api\SmsSubscriptionRepositoryInterface
     */
    private $smsSubscriptionRepository;
    /**
     * @var \Mobtexting\SMSNotifications\Api\SmsSubscriptionManagementInterface
     */
    private $smsSubscriptionManagement;
    /**
     * @var \Mobtexting\SMSNotifications\Model\SmsSender|\Mobtexting\SMSNotifications\Model\SmsSender\WelcomeSender
     */
    private $smsSender;
    /**
     * @var \Mobtexting\SMSNotifications\Api\MobileTelephoneNumberManagementInterface
     */
    private $mobileTelephoneNumberManagement;

    public function __construct(
        Context $context,
        CustomerSession $customerSession,
        LoggerInterface $logger,
        SmsSubscriptionRepositoryInterface $smsSubscriptionRepository,
        SmsSubscriptionManagementInterface $smsSubscriptionManagement,
        MobileTelephoneNumberManagementInterface $mobileTelephoneNumberManagement,
        SmsSender $smsSender
    ) {
        parent::__construct($context);

        $this->customerSession = $customerSession;
        $this->logger = $logger;
        $this->smsSubscriptionRepository = $smsSubscriptionRepository;
        $this->smsSubscriptionManagement = $smsSubscriptionManagement;
        $this->smsSender = $smsSender;
        $this->mobileTelephoneNumberManagement = $mobileTelephoneNumberManagement;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $customerId = $this->customerSession->getCustomerId();
        $selectedSmsTypes = $this->getRequest()->getParam('sms_types', []);

        $resultRedirect->setPath('*/*/manage');

        if ($customerId === null) {
            $this->messageManager->addErrorMessage(
                __('Something went wrong while saving your text notification preferences.')
            );
            $this->logger->critical(__('Could not get ID of customer to save SMS preferences for.'));

            return $resultRedirect;
        }

        $subscribedSmsTypes = $this->smsSubscriptionRepository->getListByCustomerId((int)$customerId)->getItems();

        if (count($subscribedSmsTypes) > 0) {
            $this->removeSubscriptions($subscribedSmsTypes, $selectedSmsTypes, $customerId);

            $selectedSmsTypes = array_diff($selectedSmsTypes, array_column($subscribedSmsTypes, 'sms_type'));
        }

        if (count($selectedSmsTypes) > 0) {
            $this->createSubscriptions($selectedSmsTypes, $customerId);
        }

        $mobileNumberUpdated = $this->updateMobileTelephoneNumber();

        if ($mobileNumberUpdated) {
            $this->sendWelcomeMessage();
        }

        return $resultRedirect;
    }

    /**
     * {@inheritdoc}
     */
    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $resultRedirect->setPath('*/*/manage');

        return new InvalidRequestException($resultRedirect, [__('Invalid Form Key. Please refresh the page.')]);
    }

    /**
     * {@inheritdoc}
     */
    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return null;
    }

    /**
     * @param \Mobtexting\SMSNotifications\Model\SmsSubscription[] $subscribedSmsTypes
     * @param string[] $selectedSmsTypes
     * @param string|int $customerId
     */
    private function removeSubscriptions(array &$subscribedSmsTypes, array $selectedSmsTypes, $customerId): int
    {
        $messages = [
            'error' => [
                'one' => 'You could not be unsubscribed from 1 text notification.',
                'multiple' => 'You could not be unsubscribed from %1 text notifications.'
            ],
            'success' => [
                'one' => 'You have been unsubscribed from 1 text notification.',
                'multiple' => 'You have been unsubscribed from %1 text notifications.'
            ]
        ];

        return $this->smsSubscriptionManagement->removeSubscriptions(
            $subscribedSmsTypes,
            $selectedSmsTypes,
            (int)$customerId,
            $messages
        );
    }

    /**
     * @param string[] $selectedSmsTypes
     * @param string|int $customerId
     */
    private function createSubscriptions(array $selectedSmsTypes, $customerId): int
    {
        $messages = [
            'error' => [
                'one' => 'You could not be subscribed to 1 text notification.',
                'multiple' => 'You could not be subscribed to %1 text notifications.'
            ],
            'success' => [
                'one' => 'You have been subscribed to 1 text notification.',
                'multiple' => 'You have been subscribed to %1 text notifications.'
            ]
        ];

        return $this->smsSubscriptionManagement->createSubscriptions($selectedSmsTypes, (int)$customerId, $messages);
    }

    private function updateMobileTelephoneNumber(): bool
    {
        $newPrefix = $this->getRequest()->getParam('sms_mobile_phone_prefix', '');
        $newNumber = $this->getRequest()->getParam('sms_mobile_phone_number', '');
        $customer = $this->customerSession->getCustomerDataObject();
        $numberUpdated = $this->mobileTelephoneNumberManagement->updateNumber($newPrefix, $newNumber, $customer);

        if (!$numberUpdated) {
            if ($numberUpdated === false) {
                $this->messageManager->addErrorMessage(__('Your mobile telephone number could not be updated.'));
            }

            return false;
        }

        $this->messageManager->addSuccessMessage(__('Your mobile telephone number has been updated.'));

        return true;
    }

    private function sendWelcomeMessage(): bool
    {
        return $this->smsSender->send($this->customerSession->getCustomer());
    }
}
