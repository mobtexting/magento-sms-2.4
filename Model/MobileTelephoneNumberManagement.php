<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications
 *
 * @package Mobtexting\SMSNotifications\Model
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Model;

use Mobtexting\SMSNotifications\Api\MobileTelephoneNumberManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\State\InputMismatchException;
use Psr\Log\LoggerInterface;

/**
 * Mobile Telephone Number Management Service
 *
 * @package Mobtexting\SMSNotifications\Model
 */
class MobileTelephoneNumberManagement implements MobileTelephoneNumberManagementInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;
    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    private $customerRepository;

    public function __construct(LoggerInterface $logger, CustomerRepositoryInterface $customerRepository)
    {
        $this->logger = $logger;
        $this->customerRepository = $customerRepository;
    }

    public function updateNumber(string $newPrefix, string $newNumber, CustomerInterface $customer): ?bool
    {
        $mobilePhonePrefixAttribute = $customer->getCustomAttribute('sms_mobile_phone_prefix');
        $mobilePhoneNumberAttribute = $customer->getCustomAttribute('sms_mobile_phone_number');
        $haveChanges = false;

        if (!empty($newPrefix) && empty($newNumber)) {
            return null;
        }

        if ($mobilePhonePrefixAttribute !== null) {
            $existingPrefix = $mobilePhonePrefixAttribute->getValue() ?? '';
        } else {
            $existingPrefix = '';
        }

        if ($mobilePhoneNumberAttribute !== null) {
            $existingNumber = $mobilePhoneNumberAttribute->getValue() ?? '';
        } else {
            $existingNumber = '';
        }

        if ($existingPrefix !== $newPrefix) {
            $customer->setCustomAttribute('sms_mobile_phone_prefix', $newPrefix);

            $haveChanges = true;
        }

        if ($existingNumber !== $newNumber) {
            $customer->setCustomAttribute('sms_mobile_phone_number', $newNumber);

            $haveChanges = true;
        }

        if (!$haveChanges) {
            return null;
        }

        try {
            $this->customerRepository->save($customer);
        } catch (InputException | InputMismatchException | LocalizedException $e) {
            $this->logger->critical(
                __('Could not save mobile telephone number. Error: %1', $e->getMessage()),
                [
                    'customer_id' => $customer->getId(),
                    'mobile_phone_prefix' => $newPrefix,
                    'mobile_phone_number' => $newNumber
                ]
            );

            return false;
        }

        return true;
    }
}
