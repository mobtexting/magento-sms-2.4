<?php
/**
 * Mobtexting SMS Notifications
 * 
 * Sends transactional SMS notifications
 *
 * @package Mobtexting\SMSNotifications\Model\ResourceModel\TelephonePrefix
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Model\ResourceModel\TelephonePrefix;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Mobtexting\SMSNotifications\Model\TelephonePrefix as TelephonePrefixModel;
use Mobtexting\SMSNotifications\Model\ResourceModel\TelephonePrefix as TelephonePrefixResource;

/**
 * Telephone Prefix Data Collection
 *
 * @package Mobtexting\SMSNotifications\Model\ResourceModel\TelephonePrefix
 * @author Joseph Leedy <joseph@Mobtexting.com>
 */
class Collection extends AbstractCollection
{
    /**
     * {@inheritdoc}
     */
    protected $_idFieldName = 'country_code';
    
    protected function _construct()
    {
        $this->_init(TelephonePrefixModel::class, TelephonePrefixResource::class);
    }
}
