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
 * Success Result Entity Interface
 *
 * @package Mobtexting\SMSNotifications\Gateway\Entity
 */
interface SuccessResultInterface extends ResultInterface
{
    public function setMessageId(string $messageId): void;

    public function getMessageId(): string;

    public function setResultCode(int $resultCode): void;

    public function getResultCode(): int;

    public function setDescription(string $description): void;

    public function getDescription(): string;

    public function setSmsCount(int $smsCount): void;

    public function getSmsCount(): int;
}
