<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications
 *
 * @package Mobtexting\SMSNotifications\Api
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Api;

/**
 * Message Variables
 *
 * @package Mobtexting\SMSNotifications\Api
 * @api
 */
interface MessageVariablesInterface
{
    public function getVariables(): array;
}
