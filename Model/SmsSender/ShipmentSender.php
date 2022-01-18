<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications
 *
 * @package Mobtexting\SMSNotifications\Model
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Model\SmsSender;

use Mobtexting\SMSNotifications\Api\ConfigInterface;
use Mobtexting\SMSNotifications\Api\SmsSubscriptionRepositoryInterface;
use Mobtexting\SMSNotifications\Model\MessageService;
use Mobtexting\SMSNotifications\Model\SmsSender;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Sales\Api\Data\ShipmentExtensionFactory;
use Magento\Store\Api\StoreRepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * Shipment SMS Sender
 *
 * @package Mobtexting\SMSNotifications\Model\SmsSender
 * @api
 *
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */
class ShipmentSender extends SmsSender
{
    /**
     * @var \Magento\Sales\Api\Data\ShipmentExtensionFactory
     */
    private $shipmentExtensionFactory;

    public function __construct(
        LoggerInterface $logger,
        StoreRepositoryInterface $storeRepository,
        CustomerRepositoryInterface $customerRepository,
        ConfigInterface $config,
        SmsSubscriptionRepositoryInterface $subscriptionRepository,
        MessageService $messageService,
        ShipmentExtensionFactory $shipmentExtensionFactory
    ) {
        parent::__construct(
            $logger,
            $storeRepository,
            $customerRepository,
            $config,
            $subscriptionRepository,
            $messageService
        );

        $this->shipmentExtensionFactory = $shipmentExtensionFactory;
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel|\Magento\Sales\Api\Data\ShipmentInterface|\Magento\Sales\Model\Order\Shipment $shipment
     */
    public function send(AbstractModel $shipment): bool
    {
        $storeId = (int)$shipment->getStoreId();
        $websiteId = $this->getWebsiteIdByStoreId($storeId);
        /** @var \Magento\Sales\Api\Data\ShipmentExtensionInterface $shipmentExtension */
        $shipmentExtension = $shipment->getExtensionAttributes() ?? $this->shipmentExtensionFactory->create();

        if (
            !$this->isModuleEnabled($websiteId)
            || $shipment->getOrder()->getCustomerIsGuest()
            || $shipmentExtension->getIsSmsNotificationSent() === true
        ) {
            return false;
        }

        $customerId = (int)$shipment->getCustomerId();
        $customer = $this->getCustomerById($customerId);

        if ($customer === null) {
            return false;
        }

        $messageRecipient = $this->getCustomerMobilePhoneNumber($customer);

        if (
            !in_array('order_shipped', $this->getCustomerSmsSubscriptions($customerId), true)
            || $messageRecipient === null
        ) {
            return false;
        }

        $this->messageService->setShipment($shipment);
        $this->messageService->setOrder($shipment->getOrder());

        $messageTemplate = $this->config->getOrderShippedTemplate($storeId);
        $messageSent = $this->messageService->sendMessage($messageTemplate, $messageRecipient, 'shipment');

        $shipmentExtension->setIsSmsNotificationSent(true);

        $shipment->setExtensionAttributes($shipmentExtension);

        return $messageSent;
    }
}
