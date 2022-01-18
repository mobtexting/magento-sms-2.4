<?php
/**
 * Mobtexting SMS Notifications 
 *
 * Sends transactional SMS notifications
 *
 * @package Mobtexting\SMSNotifications\Model\Config\Source
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Source Type Configuration Field Source Model
 *
 * @package Mobtexting\SMSNotifications\Model\Config\Source
 */
class SourceType implements ArrayInterface
{
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'SHORTNUMBER',
                'label' => __('Short Number')
            ],
            [
                'value' => 'ALPHANUMERIC',
                'label' => __('Alphanumeric')
            ],
            [
                'value' => 'MSISDN',
                'label' => __('Phone Number')
            ]
        ];
    }
}
