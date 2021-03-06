<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications
 *
 * @package Mobtexting\SMSNotifications\Model\MessageVariables
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Model\MessageVariables;

use Mobtexting\SMSNotifications\Api\MessageVariablesInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\UrlInterface as UrlBuilder;
use Magento\Sales\Model\Order\Shipment;
use Magento\Store\Model\ScopeInterface;

/**
 * Shipment Message Variables
 *
 * @package Mobtexting\SMSNotifications\Model\MessageVariables
 */
class ShipmentVariables implements MessageVariablesInterface
{
    /**
     * @var \Magento\Framework\UrlInterface
     */
    private $urlBuilder;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var \Magento\Sales\Model\Order\Shipment
     */
    private $shipment;

    public function __construct(UrlBuilder $urlBuilder, ScopeConfigInterface $scopeConfig)
    {
        $this->urlBuilder = $urlBuilder;
        $this->scopeConfig = $scopeConfig;
    }

    public function getVariables(): array
    {
        if ($this->shipment === null) {
            return [];
        }

        $order = $this->shipment->getOrder();

        return [
            'order_id' => $order->getIncrementId(),
            'order_url' => $this->urlBuilder->getUrl('sales/order/view', ['order_id' => $order->getEntityId()]),
            'tracking_numbers' => $this->getShipmentTrackingNumbers(),
            'customer_name' => $order->getCustomerFirstname() . ' ' . $order->getCustomerLastname(),
            'customer_first_name' => $order->getCustomerFirstname(),
            'customer_last_name' => $order->getCustomerLastname(),
            'store_name' => $this->getStoreNameById((int)$order->getStoreId(), $order->getStoreName() ?? ''),
        ];
    }

    public function setShipment(Shipment $shipment): self
    {
        $this->shipment = $shipment;

        return $this;
    }

    private function getStoreNameById(int $storeId, string $default): string
    {
        try {
            $storeName = $this->scopeConfig->getValue(
                'general/store_information/name',
                ScopeInterface::SCOPE_STORE,
                $storeId
            );
        } catch (\Exception $e) {
            $storeName = null;
        }

        if ($storeName === null) {
            if (strpos($default, "\n") !== false) {
                $default = explode("\n", $default)[1];
            }

            $storeName = $default;
        }

        return $storeName;
    }

    private function getShipmentTrackingNumbers(): string
    {
        $trackingNumbers = [];

        if ($this->shipment === null) {
            return '';
        }

        $tracks = $this->shipment->getAllTracks();

        /** @var \Magento\Shipping\Model\Order\Track $track */
        foreach ($tracks as $track) {
            $trackingNumbers[] = $track->getTitle() . ': ' . $track->getNumber();
        }

        return implode(', ', $trackingNumbers);
    }
}
