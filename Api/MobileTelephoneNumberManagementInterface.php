<?php
/**
 * Mobtexting SMS Notifications powered by LINK Mobility
 *
 * Sends transactional SMS notifications 
 *
 * @package Mobtexting\SMSNotifications\Api
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Api;

use Magento\Customer\Api\Data\CustomerInterface;

/**
 * Mobile Telephone Number Management Service Interface
 *
 * @package Mobtexting\SMSNotifications\Api
 * @api
 */
interface MobileTelephoneNumberManagementInterface
{
    public function updateNumber(string $newPrefix, string $newNumber, CustomerInterface $customer): ?bool;
}
