<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications 
 * 
 * @package Mobtexting\SMSNotifications\Model\ResourceModel\SmsSubscription\Grid
 * 
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Ui\Component\Listing\Column;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * SMS Subscription Status Column
 *
 * @package Mobtexting\SMSNotifications\Ui\Component\Listing\Column
 */
class SmsSubscriptionStatus extends Column implements OptionSourceInterface
{
    /**
     * {@inheritdoc}
     */
    public function prepareDataSource(array $dataSource): array
    {
        $dataSource = parent::prepareDataSource($dataSource);

        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        foreach ($dataSource['data']['items'] as &$item) {
            $item['status'] = $item['is_active'] ? __('Subscribed') : __('Not Subscribed');
        }

        return $dataSource;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray(): array
    {
        return [
            [
                'value' => '0',
                'label' => __('Subscribed')
            ],
            [
                'value' => '1',
                'label' => __('Not Subscribed')
            ]
        ];
    }
}
