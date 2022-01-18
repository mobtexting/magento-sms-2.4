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

use Mobtexting\SMSNotifications\Gateway\Entity\DCS;
use Mobtexting\SMSNotifications\Gateway\Entity\TON;
use Mobtexting\SMSNotifications\Gateway\Hydrator\MessageEntity as MessageEntityHydrator;
use Mobtexting\SMSNotifications\Gateway\Hydrator\Strategy\Enum as EnumStrategy;

/**
 * Message Entity Hydrator Factory
 *
 * @package Mobtexting\SMSNotifications\Gateway\Factory
 */
class MessageEntityHydratorFactory
{
    public function create(): MessageEntityHydrator
    {
        $messageHydrator = new MessageEntityHydrator();

        $messageHydrator->addStrategy('sourceTON', new EnumStrategy(TON::class));
        $messageHydrator->addStrategy('destinationTON', new EnumStrategy(TON::class));
        $messageHydrator->addStrategy('dcs', new EnumStrategy(DCS::class));

        return $messageHydrator;
    }
}
