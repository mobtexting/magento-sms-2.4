<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications
 *
 * @package Mobtexting\SMSNotifications\Plugin\Sales\Model
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Plugin\Sales\Model;

use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Model\Order;

/**
 * Plug-in for {@see \Magento\Sales\Model\Order}
 *
 * @package Mobtexting\SMSNotifications\Plugin\Sales\Model
 */
class OrderPlugin
{
    /**
     * @var \Magento\Sales\Api\Data\OrderExtensionFactory
     */
    private $orderExtensionFactory;

    public function __construct(OrderExtensionFactory $orderExtensionFactory)
    {
        $this->orderExtensionFactory = $orderExtensionFactory;
    }

    public function afterUnhold(Order $subject): Order
    {
        $orderExtension = $subject->getExtensionAttributes() ?? $this->orderExtensionFactory->create();

        $orderExtension->setIsOrderHoldReleased(true);

        $subject->setExtensionAttributes($orderExtension);

        return $subject;
    }
}
