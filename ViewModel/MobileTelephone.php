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

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Mobile Telephone View Model
 *
 * @package Mobtexting\SMSNotifications\ViewModel
 */
class MobileTelephone implements ArgumentInterface
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    public function __construct(CustomerSession $customerSession)
    {
        $this->customerSession = $customerSession;
    }

    public function getPrefix(): ?string
    {
        $mobilePrefixAttribute = $this->customerSession->getCustomerData()
            ->getCustomAttribute('sms_mobile_phone_prefix');

        if ($mobilePrefixAttribute === null) {
            return null;
        }

        return $mobilePrefixAttribute->getValue();
    }

    public function getNumber(): ?string
    {
        $mobileNumberAttribute = $this->customerSession->getCustomerData()
            ->getCustomAttribute('sms_mobile_phone_number');

        if ($mobileNumberAttribute === null) {
            return null;
        }

        return $mobileNumberAttribute->getValue();
    }
}
