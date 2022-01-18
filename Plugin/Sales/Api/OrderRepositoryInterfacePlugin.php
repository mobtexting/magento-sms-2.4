<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications
 *
 * @package Mobtexting\SMSNotifications\Plugin\Sales\Api
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Plugin\Sales\Api;

use Mobtexting\SMSNotifications\Model\SmsSender;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

/**
 * Plug-in for {@see \Magento\Sales\Api\OrderRepositoryInterface}
 *
 * @package Mobtexting\SMSNotifications\Plugin\Sales\Api
 */
class OrderRepositoryInterfacePlugin
{
    /**
     * @var \Mobtexting\SMSNotifications\Model\SmsSender|\Mobtexting\SMSNotifications\Model\SmsSender\OrderSender
     */
    private $smsSender;

    public function __construct(SmsSender $smsSender)
    {
        $this->smsSender = $smsSender;
    }

    public function afterSave(OrderRepositoryInterface $subject, OrderInterface $order): OrderInterface
    {
        $this->smsSender->send($order);

        return $order;
    }
}
