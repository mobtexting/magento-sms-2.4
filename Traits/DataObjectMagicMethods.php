<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications
 *
 * @package Mobtexting\SMSNotifications\Traits
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Traits;

/**
 * Data Object Magic Methods
 *
 * @package Mobtexting\SMSNotifications\Traits
 */
trait DataObjectMagicMethods
{
    /**
     * @param mixed $value
     */
    public function __set(string $key, $value): void
    {
        $this->setData($key, $value);
    }

    /**
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this->getData($key);
    }

    public function __isset(string $key): bool
    {
        return $this->hasData($key);
    }

    public function __unset(string $key): void
    {
        $this->unsetData($key);
    }
}
