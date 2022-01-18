<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications
 *
 * @package Mobtexting\SMSNotifications\ViewModel
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\ViewModel;

use Mobtexting\SMSNotifications\Model\Source\SmsType as SmsTypeSource;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * SMS Types View Model
 *
 * @package Mobtexting\SMSNotifications\ViewModel
 */
class SmsTypes implements ArgumentInterface
{
    /**
     * @var \Mobtexting\SMSNotifications\Model\Source\SmsType
     */
    private $smsTypeSource;

    public function __construct(SmsTypeSource $smsTypeSource)
    {
        $this->smsTypeSource = $smsTypeSource;
    }

    public function getSmsTypes(string $field = ''): array
    {
        $smsTypes = $this->smsTypeSource->toArray();

        if (trim($field) !== '') {
            $smsTypes = array_column($smsTypes, $field);
        }

        return $smsTypes;
    }

    public function getGroupedSmsTypes(): array
    {
        $groupedSmsTypes = [];
        $i = 0;

        foreach ($this->smsTypeSource->toArray() as $smsType) {
            $key = array_search($smsType['group'], array_column($groupedSmsTypes, 'groupName'));

            if ($key === false) {
                $key = $i++;
                $groupedSmsTypes[$key] = [
                    'groupName' => $smsType['group'],
                    'title' => ucwords(str_replace('_', ' ', $smsType['group'])),
                    'smsTypes' => []
                ];
            }

            $groupedSmsTypes[$key]['smsTypes'][] = [
                'code' => $smsType['code'],
                'description' => $smsType['description']
            ];
        }

        return $groupedSmsTypes;
    }
}
