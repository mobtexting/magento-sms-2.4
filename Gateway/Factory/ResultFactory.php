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

use Mobtexting\SMSNotifications\Gateway\Entity\ErrorResult;
use Mobtexting\SMSNotifications\Gateway\Entity\ResultInterface;
use Mobtexting\SMSNotifications\Gateway\Entity\SuccessResult;

/**
 * Result Factory
 *
 * @package Mobtexting\SMSNotifications\Gateway\Factory
 */
class ResultFactory
{
    public function create(string $type, array $data = []): ResultInterface
    {
        switch ($type) {
            case 'success':
                $class = SuccessResult::class;
                break;
            case 'error':
                $class = ErrorResult::class;
                break;
            default:
                throw new \InvalidArgumentException('Invalid result type.');
        }

        return new $class($data);
    }
}
