<?php
/**
 * Mobtexting SMS Notifications 
 *
 * Sends transactional SMS notifications 
 *
 * @package Mobtexting\SMSNotifications\Gateway
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Gateway;

use Mobtexting\SMSNotifications\Gateway\Entity\ResultInterface;

/**
 * API Client Interface
 *
 * @package Mobtexting\SMSNotifications\Gateway
 */
interface ApiClientInterface
{
    public const HTTP_METHOD_GET = 'GET';
    public const HTTP_METHOD_POST = 'POST';

    public function setUri(string $uri): void;

    public function setUsername(string $username): void;

    public function setPassword(string $password): void;

    /**
     * @param string[]|\Mobtexting\SMSNotifications\Gateway\Entity\MessageInterface $data
     */
    public function setData($data): void;

    public function setHeaders(array $headers): void;

    public function setHttpMethod(string $httpMethod): void;

    /**
     * @throws \Mobtexting\SMSNotifications\Gateway\ApiException
     */
    public function sendRequest(): void;

    public function getResult(): ResultInterface;
}
