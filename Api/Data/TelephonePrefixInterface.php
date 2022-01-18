<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications 
 *
 * @package Mobtexting\SMSNotifications\Api\Data
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Api\Data;

/**
 * Telephone Prefix Entity Interface
 *
 * @package Mobtexting\SMSNotifications\Api\Data
 * @api
 */
interface TelephonePrefixInterface
{
    public const COUNTRY_CODE = 'country_code';
    public const COUNTRY_NAME = 'country_name';
    public const PREFIX = 'prefix';

    public function setCountryCode(string $countryCode): TelephonePrefixInterface;

    public function getCountryCode(): string;

    public function setCountryName(string $countryName): TelephonePrefixInterface;

    public function getCountryName(): string;

    public function setPrefix(int $prefix): TelephonePrefixInterface;

    public function getPrefix(): int;
}
