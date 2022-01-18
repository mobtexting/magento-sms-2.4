<?php
/**
 * Wagento SMS Notifications
 *
 * Sends transactional SMS notifications
 *
 * @package Wagento\SMSNotifications\Gateway\Entity
 * 
 */

declare(strict_types=1);

namespace Wagento\SMSNotifications\Gateway\Entity;

/**
 * Error Result Entity Interface
 *
 * @package Wagento\SMSNotifications\Gateway\Entity
 */
interface ErrorResultInterface extends ResultInterface
{
    public function setStatus(int $status): void;

    public function getStatus(): int;

    public function setDescription(string $description): void;

    public function getDescription(): string;

    public function setTranslatedDescription(?string $translatedDescription): void;

    public function getTranslatedDescription(): ?string;
}
