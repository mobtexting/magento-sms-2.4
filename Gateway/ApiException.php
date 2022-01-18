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

/**
 * API Exception
 *
 * @package Mobtexting\SMSNotifications\Gateway
 */
class ApiException extends \Exception
{
    private $responseData = [];

    /**
     * @phpcs:disable Generic.Files.LineLength.TooLong
     */
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null, array $responseData = [])
    {
        parent::__construct($message, $code, $previous);

        $this->responseData = $responseData;
    }

    public function getResponseData(): array
    {
        return $this->responseData;
    }
}
