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
use Magento\Sales\Api\CreditmemoRepositoryInterface;
use Magento\Sales\Api\Data\CreditmemoInterface;

/**
 * Plug-in for {@see \Magento\Sales\Api\CreditmemoRepositoryInterface}
 *
 * @package Mobtexting\SMSNotifications\Plugin\Sales\Api
 *
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */
class CreditmemoRepositoryInterfacePlugin
{
    /**
     * @var \Mobtexting\SMSNotifications\Model\SmsSender|\Mobtexting\SMSNotifications\Model\SmsSender\CreditmemoSender
     */
    private $smsSender;

    public function __construct(SmsSender $smsSender)
    {
        $this->smsSender = $smsSender;
    }

    public function afterSave(CreditmemoRepositoryInterface $subject, CreditmemoInterface $creditmemo): CreditmemoInterface
    {
        $this->smsSender->send($creditmemo);

        return $creditmemo;
    }
}
