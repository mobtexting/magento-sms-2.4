<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications
 *
 * @package Mobtexting\SMSNotifications\Gateway\Entity
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Gateway\Entity;

/**
 * Result Entity Interface
 *
 * @package Mobtexting\SMSNotifications\Gateway\Entity
 */
interface ResultInterface
{
    public function getType(): string;

    public function getCode(): int;

    public function getMessage(): string;

    public function toArray(): array;
}
