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
 * Error Result Entity
 *
 * @package Mobtexting\SMSNotifications\Gateway\Entity
 */
class ErrorResult implements ErrorResultInterface
{
    private const TYPE = 'error';

    private $status = 0;
    private $description = '';
    private $translatedDescription;

    public function __construct(array $data = [])
    {
        if (array_key_exists('status', $data)) {
            $this->setStatus($data['status']);
        }

        if (array_key_exists('description', $data)) {
            $this->setDescription($data['description']);
        }

        if (array_key_exists('translatedDescription', $data)) {
            $this->setTranslatedDescription($data['translatedDescription']);
        }
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setTranslatedDescription(?string $translatedDescription): void
    {
        $this->translatedDescription = $translatedDescription;
    }

    public function getTranslatedDescription(): ?string
    {
        return $this->translatedDescription;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getCode(): int
    {
        return $this->getStatus();
    }

    public function getMessage(): string
    {
        return $this->getDescription();
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'description' => $this->description,
            'translatedDescription' => $this->translatedDescription
        ];
    }
}
