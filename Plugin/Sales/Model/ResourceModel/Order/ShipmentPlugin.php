<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications
 *
 * @package Mobtexting\SMSNotifications\Plugin\Sales\Model\ResourceModel\Order
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Plugin\Sales\Model\ResourceModel\Order;

use Mobtexting\SMSNotifications\Model\SmsSender;
use Magento\Sales\Model\Order\Shipment;
use Magento\Sales\Model\ResourceModel\Order\Shipment as ShipmentResource;

/**
 * Plug-in for {@see \Magento\Sales\Model\ResourceModel\Order\Shipment}
 *
 * @package Mobtexting\SMSNotifications\Plugin\Sales\Model\ResourceModel\Order
 */
class ShipmentPlugin
{
    /**
     * @var \Mobtexting\SMSNotifications\Model\SmsSender|\Mobtexting\SMSNotifications\Model\SmsSender\ShipmentSender
     */
    private $smsSender;

    public function __construct(SmsSender $smsSender)
    {
        $this->smsSender = $smsSender;
    }

    public function afterSave(ShipmentResource $subject, ShipmentResource $result, Shipment $shipment): ShipmentResource
    {
        $this->smsSender->send($shipment);

        return $result;
    }
}
