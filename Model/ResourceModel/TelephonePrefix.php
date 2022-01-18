<?php
/**
 * Mobtexting SMS Notifications
 * 
 * Sends transactional SMS notifications 
 *
 * @package Mobtexting\SMSNotifications\Model\ResourceModel
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Telephone Prefix Data Resource Model
 *
 * @package Mobtexting\SMSNotifications\Model\ResourceModel
 */
class TelephonePrefix extends AbstractDb
{
    public const TABLE_NAME = 'directory_telephone_prefix';
    public const IDENTITY_COLUMN = 'country_code';

    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, self::IDENTITY_COLUMN);
    }

    /**
     * {@inheritdoc}
     *
     * Prevent data from being saved as this entity is read-only.
     */
    public function save(AbstractModel $object)
    {
        return $this;
    }
}
