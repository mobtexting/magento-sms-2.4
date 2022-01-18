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

use MyCLabs\Enum\Enum;

/**
 * TON (Type of Number) Entity
 *
 * @package Mobtexting\SMSNotifications\Gateway\Entity
 */
class TON extends Enum
{
    /**
     * Short number; 1-14 digits depending on country.
     */
    public const SHORTNUMBER = 'SHORTNUMBER';
    /**
     * Up to 11 valid GSM characters in the range a-z, A-Z, 0-9.
     */
    public const ALPHANUMERIC = 'ALPHANUMERIC';
    /**
     * Mobile number in international format beginning with a + (i.e. +1-555-555-1234)
     */
    public const MSISDN = 'MSISDN';
}
