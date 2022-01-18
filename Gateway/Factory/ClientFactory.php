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

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

/**
 * Client Factory
 *
 * @package Mobtexting\SMSNotifications\Gateway\Factory
 */
class ClientFactory
{
    public function create(array $config): ClientInterface
    {
        return new Client($config);
    }
}
