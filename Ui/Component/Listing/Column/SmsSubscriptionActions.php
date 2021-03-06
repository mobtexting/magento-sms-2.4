<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications
 *
 * @package Mobtexting\SMSNotifications\Ui\Component\Listing\Column
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * SMS Subscription Actions Listing Column
 *
 * @package Mobtexting\SMSNotifications\Ui\Component\Listing\Column
 * @author Joseph Leedy <joseph@Mobtexting.com>
 */
class SmsSubscriptionActions extends Column
{
    /**
     * @var \Magento\Framework\UrlInterface
     */
    private $urlBuilder;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);

        $this->urlBuilder = $urlBuilder;
    }

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
            if ($item['is_active'] === 1) {
                $item[$this->getData('name')]['unsubscribe'] = [
                    'href' => $this->urlBuilder->getUrl(
                        'sms_notifications/subscription/delete',
                        ['sms_subscription_id' => $item['sms_subscription_id']]
                    ),
                    'label' => __('Unsubscribe'),
                    'confirm' => [
                        'title' => __('Delete SMS Subscription'),
                        'message' => __('Are you sure that you want to delete this SMS Subscription?')
                    ],
                    'post' => true
                ];
            } else {
                $item[$this->getData('name')]['subscribe'] = [
                    'href' => $this->urlBuilder->getUrl(
                        'sms_notifications/subscription/create',
                        ['sms_type' => $item['sms_type']]
                    ),
                    'label' => __('Subscribe'),
                ];
            }
        }

        return $dataSource;
    }
}
