<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications 
 *
 * @package Mobtexting\SMSNotifications\Model\Data
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Model\Data;

use Mobtexting\SMSNotifications\Api\Data\TelephonePrefixInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * Telephone Prefix Entity
 *
 * @package Mobtexting\SMSNotifications\Model\Data
 */
class TelephonePrefix extends AbstractSimpleObject implements TelephonePrefixInterface
{
    public function setCountryCode(string $countryCode): TelephonePrefixInterface
    {
        return $this->setData(self::COUNTRY_CODE, $countryCode);
    }

    public function getCountryCode(): string
    {
        return (string)$this->_get(self::COUNTRY_CODE);
    }

    public function setCountryName(string $countryName): TelephonePrefixInterface
    {
        return $this->setData(self::COUNTRY_NAME, $countryName);
    }

    public function getCountryName(): string
    {
        return (string)$this->_get(self::COUNTRY_NAME);
    }

    public function setPrefix(int $prefix): TelephonePrefixInterface
    {
        return $this->setData(self::PREFIX, $prefix);
    }

    public function getPrefix(): int
    {
        return (int)$this->_get(self::PREFIX);
    }
}
