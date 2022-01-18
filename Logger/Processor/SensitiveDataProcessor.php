<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications 
 *
 * @package Mobtexting\SMSNotifications\Logger\Handler
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Logger\Processor;

/**
 * Sensitive Data Log Processor
 *
 * Removes sensitive information from the log file.
 *
 * @package Mobtexting\SMSNotifications\Logger\Processor
 */
class SensitiveDataProcessor
{
    /**
     * @var string[]
     */
    private $sensitiveFields = ['password'];

    /**
     * @param string[] $sensitiveFields
     */
    public function __construct(array $sensitiveFields = [])
    {
        $this->sensitiveFields = array_merge($this->sensitiveFields, $sensitiveFields);
    }

    public function __invoke(array $record): array
    {
        $record['context'] = $this->scrub($record['context']);
        $record['extra'] = $this->scrub($record['extra']);

        return $record;
    }

    /**
     * @param array|object $data
     * @return array|object
     */
    private function scrub($data)
    {
        if (!is_array($data) && !is_object($data)) {
            return [];
        }

        foreach ($data as $name => $field) {
            if (is_string($field) && !is_numeric($field) && $this->isJson($field)) {
                if (is_array($data)) {
                    $data[$name] = json_encode($this->scrub(json_decode($field)));
                } else {
                    $data->{$name} = json_encode($this->scrub(json_decode($field)));
                }

                continue;
            }

            if (is_array($field) || is_object($field)) {
                if (is_array($data)) {
                    $data[$name] = $this->scrub($field);
                } else {
                    $data->{$name} = $this->scrub($field);
                }

                continue;
            }

            if (in_array($name, $this->sensitiveFields, true)) {
                if (is_array($data)) {
                    $data[$name] = $this->redact($field);
                } else {
                    $data->{$name} = $this->redact($field);
                }
            }
        }

        return $data;
    }

    private function redact(string $text): string
    {
        return str_repeat('*', strlen($text));
    }

    private function isJson(string $string): bool
    {
        json_decode($string);

        return json_last_error() === JSON_ERROR_NONE;
    }
}
