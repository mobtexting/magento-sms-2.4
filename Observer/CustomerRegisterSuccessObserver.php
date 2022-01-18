<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications 
 *
 * @package Mobtexting\SMSNotifications\Observer
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Observer;

use Mobtexting\SMSNotifications\Api\ConfigInterface;
use Mobtexting\SMSNotifications\Api\SmsSubscriptionManagementInterface;
use Mobtexting\SMSNotifications\Model\SmsSender;
use Mobtexting\SMSNotifications\Model\Source\SmsType as SmsTypeSource;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Observer for customer_register_success event
 *
 * @package Mobtexting\SMSNotifications\Observer
 * @see \Magento\Customer\Controller\Account\CreatePost::execute()
 */
class CustomerRegisterSuccessObserver implements ObserverInterface
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    private $customerFactory;
    /**
     * @var \Mobtexting\SMSNotifications\Api\ConfigInterface
     */
    private $config;
    /**
     * @var \Mobtexting\SMSNotifications\Model\Source\SmsType
     */
    private $smsTypeSource;
    /**
     * @var \Mobtexting\SMSNotifications\Api\SmsSubscriptionManagementInterface
     */
    private $smsSubscriptionManagement;
    /**
     * @var \Mobtexting\SMSNotifications\Model\SmsSender\WelcomeSender
     */
    private $smsSender;

    public function __construct(
        RequestInterface $request,
        StoreManagerInterface $storeManager,
        CustomerFactory $customerFactory,
        ConfigInterface $config,
        SmsTypeSource $smsTypeSource,
        SmsSubscriptionManagementInterface $smsSubscriptionManagement,
        SmsSender $smsSender
    ) {
        $this->storeManager = $storeManager;
        $this->request = $request;
        $this->customerFactory = $customerFactory;
        $this->config = $config;
        $this->smsTypeSource = $smsTypeSource;
        $this->smsSubscriptionManagement = $smsSubscriptionManagement;
        $this->smsSender = $smsSender;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Customer\Api\Data\CustomerInterface $customer */
        $customer = $observer->getData('customer');
        $smsNotificationsParameters = $this->request->getParam('sms_notifications', []);

        try {
            $websiteId = (int)$this->storeManager->getStore()->getWebsiteId();
        } catch (NoSuchEntityException $e) {
            $websiteId = null;
        }

        if (
            !$this->config->isEnabled($websiteId)
            || !$this->request->isPost()
            || empty($smsNotificationsParameters)
            || empty($smsNotificationsParameters['subscribed'])
        ) {
            return;
        }

        if (
            array_key_exists('sms_types', $smsNotificationsParameters)
            && trim($smsNotificationsParameters['sms_types']) !== ''
        ) {
            $smsTypes = explode(',', $smsNotificationsParameters['sms_types']);
        } else {
            $smsTypes = array_column($this->smsTypeSource->toArray(), 'code');
        }

        $createdSmsSubscriptions = $this->smsSubscriptionManagement->createSubscriptions(
            $smsTypes,
            (int)$customer->getId()
        );

        if ($createdSmsSubscriptions > 0) {
            $this->sendWelcomeMessage($customer);
        }
    }

    private function sendWelcomeMessage(CustomerInterface $customerData): void
    {
        /** @var \Magento\Customer\Model\Customer $customer */
        $customer = $this->customerFactory->create()->updateData($customerData);

        $this->smsSender->send($customer);
    }
}
