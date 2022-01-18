<?php
/**
 * Mobtexting SMS Notifications 
 *
 * Sends transactional SMS notifications 
 *
 * @package Mobtexting\SMSNotifications\Gateway\Factory
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Gateway\Factory;

use Mobtexting\SMSNotifications\Gateway\Entity\Message;
use Mobtexting\SMSNotifications\Gateway\Entity\MessageInterface;

/**
 * Message Entity Factory
 *
 * @package Mobtexting\SMSNotifications\Gateway\Factory
 */
class MessageFactory
{
    public function create(
        string $source = null,
        string $destination = null,
        string $userData = null,
        string $platformId = null,
        string $platformPartnerId = null
    ): MessageInterface {
        return new Message($source, $destination, $userData, $platformId, $platformPartnerId);
    }
}
