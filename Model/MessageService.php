<?php
/**
 * Mobtexting SMS Notifications powered by LINK Mobility
 *
 * Sends transactional SMS notifications 
 *
 * @package Mobtexting\SMSNotifications\Model
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Model;

use Mobtexting\SMSNotifications\Api\ConfigInterface;
use Mobtexting\SMSNotifications\Factory\MessageVariablesFactory;
use Mobtexting\SMSNotifications\Gateway\ApiClientInterface;
use Mobtexting\SMSNotifications\Gateway\ApiException;
use Mobtexting\SMSNotifications\Gateway\Factory\MessageFactory;
use Mobtexting\SMSNotifications\Util\TemplateProcessorInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\InvoiceInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\ShipmentInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Message Service
 *
 * @package Mobtexting\SMSNotifications\Model
 * @api
 */
class MessageService
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var \Mobtexting\SMSNotifications\Api\ConfigInterface
     */
    private $config;
    /**
     * @var \Mobtexting\SMSNotifications\Gateway\Factory\MessageFactory
     */
    private $messageFactory;
    /**
     * @var \Mobtexting\SMSNotifications\Factory\MessageVariablesFactory
     */
    private $messageVariablesFactory;
    /**
     * @var \Mobtexting\SMSNotifications\Util\TemplateProcessorInterface
     */
    private $templateProcessor;
    /**
     * @var \Mobtexting\SMSNotifications\Gateway\ApiClientInterface
     */
    private $apiClient;
    /**
     * @var \Magento\Sales\Api\Data\InvoiceInterface
     */
    private $invoice;
    /**
     * @var \Magento\Sales\Api\Data\OrderInterface
     */
    private $order;
    /**
     * @var \Magento\Sales\Api\Data\ShipmentInterface
     */
    private $shipment;
    /**
     * @var \Magento\Customer\Api\Data\CustomerInterface
     */
    private $customer;

    public function __construct(
        LoggerInterface $logger,
        StoreManagerInterface $storeManager,
        ConfigInterface $config,
        MessageFactory $messageFactory,
        MessageVariablesFactory $messageVariablesFactory,
        TemplateProcessorInterface $templateProcessor,
        ApiClientInterface $apiClient
    ) {
        $this->logger = $logger;
        $this->storeManager = $storeManager;
        $this->config = $config;
        $this->messageFactory = $messageFactory;
        $this->messageVariablesFactory = $messageVariablesFactory;
        $this->templateProcessor = $templateProcessor;
        $this->apiClient = $apiClient;
    }

    public function setInvoice(InvoiceInterface $invoice): MessageService
    {
        $this->invoice = $invoice;

        return $this;
    }

    public function setOrder(OrderInterface $order): MessageService
    {
        $this->order = $order;

        return $this;
    }

    public function setShipment(ShipmentInterface $shipment): MessageService
    {
        $this->shipment = $shipment;

        return $this;
    }

    public function setCustomer(CustomerInterface $customer): MessageService
    {
        $this->customer = $customer;

        return $this;
    }

    public function sendMessage(string $message, string $to, string $messageType): bool
    {
        $to = preg_replace('/[^\+\d]+/', '', $to);
        $websiteId = $this->getWebsiteId();
        $messageEntity = $this->messageFactory->create();
        $source = $this->config->getSource($websiteId);
        $sourceType = $this->config->getSourceType($websiteId);
        $platformId = $this->config->getPlatformId($websiteId);
        $platformPartnerId = $this->config->getPlatformPartnerId($websiteId);
        $processedMessage = $this->processMessage($message, $messageType);

        if ($source === null || $sourceType === null || $platformId === null || $platformPartnerId === null) {
            $this->logger->critical(
                __('The API settings are not configured properly.'),
                [
                    'source' => $source,
                    'sourceType' => $sourceType,
                    'platformId' => $platformId,
                    'platformPartnerId' => $platformPartnerId
                ]
            );

            return false;
        }

        $messageEntity->setSource($source);
        $messageEntity->setSourceTON($sourceType);
        $messageEntity->setDestination($to);
        $messageEntity->setUserData($processedMessage);
        $messageEntity->setPlatformId($platformId);
        $messageEntity->setPlatformPartnerId($platformPartnerId);

        try {
            $this->apiClient->setUri('send');
            $this->apiClient->setUsername($this->config->getApiUser($websiteId));
            $this->apiClient->setPassword($this->config->getApiPassword($websiteId));
            $this->apiClient->setHttpMethod(ApiClientInterface::HTTP_METHOD_POST);
            $this->apiClient->setData($messageEntity);
            $this->apiClient->sendRequest();

            $result = $this->apiClient->getResult();

            $this->logger->debug(
                __('The SMS message was sent successfully.'),
                [
                    'message' => $processedMessage,
                    'result' => $result->toArray()
                ]
            );
        } catch (ApiException $e) {
            $this->logger->critical(
                __($e->getMessage()),
                [
                    'message' => $processedMessage,
                    'result' => $e->getResponseData()
                ]
            );

            return false;
        }

        return true;
    }

    private function processMessage(string $message, string $type): string
    {
        $messageVariables = $this->messageVariablesFactory->create(
            $type,
            [
                'invoice' => $this->invoice,
                'order' => $this->order,
                'shipment' => $this->shipment,
                'customer' => $this->customer,
            ]
        );

        if ($messageVariables === null) {
            return $message;
        }

        $variables = $messageVariables->getVariables();

        if (count($variables) === 0) {
            return $message;
        }

        return $this->templateProcessor->process($message, $variables);
    }

    private function getWebsiteId(): ?int
    {
        $storeId = null;

        if ($this->order !== null && $this->order->getStoreId() !== null) {
            $storeId = $this->order->getStoreId();
        }

        try {
            $websiteId = (int)$this->storeManager->getStore($storeId)->getWebsiteId();
        } catch (NoSuchEntityException $e) {
            $websiteId = null;
        }

        return $websiteId;
    }
}
