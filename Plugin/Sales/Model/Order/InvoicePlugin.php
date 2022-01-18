<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications
 *
 * @package Mobtexting\SMSNotifications\Plugin\Sales\Model\Order
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Plugin\Sales\Model\Order;

use Mobtexting\SMSNotifications\Model\SmsSender;
use Magento\Sales\Model\Order\Invoice;

/**
 * Plug-in for {@see \Magento\Sales\Model\Order\Invoice}
 *
 * @package Mobtexting\SMSNotifications\Plugin\Sales\Model\Order
 */
class InvoicePlugin
{
    /**
     * @var \Mobtexting\SMSNotifications\Model\SmsSender|\Mobtexting\SMSNotifications\Model\SmsSender\InvoiceSender
     */
    private $smsSender;

    public function __construct(SmsSender $smsSender)
    {
        $this->smsSender = $smsSender;
    }

    /**
     * @see \Magento\Sales\Model\Order\Invoice::register()
     */
    public function afterRegister(Invoice $subject): Invoice
    {
        $this->smsSender->send($subject);

        return $subject;
    }
}
