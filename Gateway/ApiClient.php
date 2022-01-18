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

use GuzzleHttp\Exception\GuzzleException;
use Mobtexting\SMSNotifications\Gateway\Entity\MessageInterface;
use Mobtexting\SMSNotifications\Gateway\Entity\ResultInterface;
use Mobtexting\SMSNotifications\Gateway\Factory\ClientFactory;
use Mobtexting\SMSNotifications\Gateway\Factory\MessageEntityHydratorFactory;
use Mobtexting\SMSNotifications\Gateway\Factory\ResultFactory;
use Psr\Http\Message\ResponseInterface;

/**
 * API Client
 *
 * @package Mobtexting\SMSNotifications\Gateway
 */
class ApiClient implements ApiClientInterface
{
    private const BASE_URL = 'https://wsx.sp247.net/sms/';

    /**
     * @var \Mobtexting\SMSNotifications\Gateway\Factory\ClientFactory
     */
    private $clientFactory;
    /**
     * @var \Mobtexting\SMSNotifications\Gateway\Factory\ResultFactory
     */
    private $resultFactory;
    /**
     * @var string
     */
    private $uri;
    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $password;
    /**
     * @var array
     */
    private $data;
    /**
     * @var array
     */
    private $headers = [];
    /**
     * @var string
     */
    private $httpMethod = self::HTTP_METHOD_GET;
    /**
     * @var array
     */
    private $errors = [];
    /**
     * @var \Mobtexting\SMSNotifications\Gateway\Entity\ResultInterface
     */
    private $result;

    public function __construct(ClientFactory $clientFactory, ResultFactory $resultFactory)
    {
        $this->clientFactory = $clientFactory;
        $this->resultFactory = $resultFactory;
    }

    public function setUri(string $uri): void
    {
        $this->uri = $uri;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @param string[]|\Mobtexting\SMSNotifications\Gateway\Entity\MessageInterface $data
     */
    public function setData($data): void
    {
        if ($data instanceof MessageInterface) {
            $data = array_filter($this->extractMessage($data));
        }

        $this->data = $data;
    }

    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    public function setHttpMethod(string $httpMethod): void
    {
        if ($httpMethod !== self::HTTP_METHOD_GET && $httpMethod !== self::HTTP_METHOD_POST) {
            throw new \InvalidArgumentException("Method \"$httpMethod\" is not an allowed method.");
        }

        $this->httpMethod = $httpMethod;
    }

    /**
     * @throws \Mobtexting\SMSNotifications\Gateway\ApiException
     *
     * @phpcs:disable Generic.Files.LineLength.TooLong
     */
    public function sendRequest(): void
    {
        if (!$this->isValid()) {
            throw new ApiException('The client was not properly configured. Errors: ' . \implode(', ', $this->errors));
        }

        $requestBody = \json_encode($this->data);

        if (\json_last_error() !== JSON_ERROR_NONE) {
            throw new ApiException('Could not encode request data as JSON. Error: ' . \json_last_error_msg());
        }

        try {
            $client = $this->clientFactory->create(['base_uri' => self::BASE_URL]);
            $response = $client->request(
                $this->httpMethod,
                self::BASE_URL . $this->uri,
                [
                    'headers' => \array_merge($this->getDefaultHeaders(), $this->headers, $this->getAuthenticationHeader()),
                    'body' => $requestBody
                ]
            );
        } catch (GuzzleException $e) {
            throw $this->getException($e);
        }

        $this->setResult($response, 'success');
    }

    public function getResult(): ResultInterface
    {
        return $this->result;
    }

    private function extractMessage(MessageInterface $message): array
    {
        $messageHydrator = (new MessageEntityHydratorFactory())->create();

        return $messageHydrator->extract($message);
    }

    private function isValid(): bool
    {
        $this->errors = [];

        if ($this->uri === null) {
            $this->errors[] = 'URI not provided';
        }

        if ($this->username === null) {
            $this->errors[] = 'API username not provided';
        }

        if ($this->password === null) {
            $this->errors[] = 'API password not provided';
        }

        if (empty($this->data)) {
            $this->errors[] = 'Request data is empty';
        }

        return !(\count($this->errors) > 0);
    }

    private function getDefaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }

    private function getAuthenticationHeader(): array
    {
        return [
            'Authorization' => 'Basic ' . \base64_encode($this->username . ':' . $this->password)
        ];
    }

    private function getException(GuzzleException $exception): ApiException
    {
        /** @var \Psr\Http\Message\ResponseInterface $response */
        $response = $exception->getResponse();

        $this->setResult($response, 'error');

        return new ApiException(
            'An error occurred while making the API request. Error: ' . $response->getStatusCode() . ' '
                . $response->getReasonPhrase(),
            0,
            null,
            $this->result->toArray()
        );
    }

    private function setResult(ResponseInterface $response, string $type): void
    {
        $responseBody = \json_decode((string)$response->getBody(), true);

        if ($responseBody === false) {
            return;
        }

        $this->result = $this->resultFactory->create($type, $responseBody);
    }
}
