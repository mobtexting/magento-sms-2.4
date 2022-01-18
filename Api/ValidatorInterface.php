<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications 
 *
 * @package Mobtexting\SMSNotifications\Api
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Api;

use Magento\Framework\Model\AbstractModel;

/**
 * SMS Subscription Validator Interface
 *
 * @package Mobtexting\SMSNotifications\Model
 * @author Joseph Leedy <joseph@Mobtexting.com>
 * @api
 */
interface ValidatorInterface
{
    /**
     * @throws \Exception
     * @throws \Zend_Validate_Exception
     */
    public function validate(AbstractModel $model): void;

    public function isValid(): bool;

    public function getMessages(): array;

    /**
     * @throws \Zend_Validate_Exception
     */
    public function getValidator(): \Zend_Validate_Interface;
}
