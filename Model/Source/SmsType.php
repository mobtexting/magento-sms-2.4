<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications
 *
 * @package Mobtexting\SMSNotifications\Ui\Component\Listing\Column
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * SMS Notification Type Option Source Model
 *
 * @package Mobtexting\SMSNotifications\Model\Source
 */
class SmsType implements OptionSourceInterface
{
    /**
     * {@inheritdoc}
     */
    public function toOptionArray(): array
    {
        $smsTypes = $this->toArray();
        $options = [];

        foreach ($smsTypes as $type) {
            $options[] = [
                'value' => $type['code'],
                'label' => $type['description']
            ];
        }

        return $options;
    }

    public function toArray(): array
    {
        return [
            /*[
                'group' => 'general',
                'code' => 'all',
                'description' => __('All notifications')
            ],*/
            [
                'group' => 'order',
                'code' => 'order_placed',
                'description' => __('Order placed successfully')
            ],
            [
                'group' => 'order',
                'code' => 'order_invoiced',
                'description' => __('Order invoiced')
            ],
            [
                'group' => 'order',
                'code' => 'order_shipped',
                'description' => __('Order shipped')
            ],
            [
                'group' => 'order',
                'code' => 'order_refunded',
                'description' => __('Order refunded')
            ],
            [
                'group' => 'order',
                'code' => 'order_canceled',
                'description' => __('Order canceled')
            ],
            [
                'group' => 'order',
                'code' => 'order_held',
                'description' => __('Order placed on hold')
            ],
            [
                'group' => 'order',
                'code' => 'order_released',
                'description' => __('Order hold released')
            ],
        ];
    }
}
