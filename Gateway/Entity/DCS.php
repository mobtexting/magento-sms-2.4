<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications
 *
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Gateway\Entity;

use MyCLabs\Enum\Enum;

/**
 * DCS Entity
 *
 * @package Mobtexting\SMSNotifications\Gateway\Entity
 */
class DCS extends Enum
{
    /**
     * GSM-7 default alphabet encoding
     */
    public const GSM = 'GSM';
    /**
     * 8-bit binary data
     */
    public const BINARY = 'BINARY';
    /**
     * UCS-2 encoding
     */
    public const UCS2 = 'UCS2';
    /**
     * Server side handling of encoding and segmenting
     */
    public const TEXT = 'TEXT';
}
