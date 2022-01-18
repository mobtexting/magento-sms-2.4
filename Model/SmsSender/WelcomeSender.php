<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications
 *
 * @package Mobtexting\SMSNotifications\Model\SmsSender
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Model\SmsSender;

use Mobtexting\SMSNotifications\Model\SmsSender;
use Magento\Framework\Model\AbstractModel;

/**
 * Welcome SMS Sender
 *
 * @package Mobtexting\SMSNotifications\Model\SmsSender
 * @api
 */
class WelcomeSender extends SmsSender
{
    /**
     * @phpcs:disable Generic.Files.LineLength.TooLong
     *
     * @param \Magento\Framework\Model\AbstractModel|\Magento\Customer\Api\Data\CustomerInterface|\Magento\Customer\Model\Customer $customer
     */
    public function send(AbstractModel $customer): bool
    {
        $websiteId = (int)$customer->getWebsiteId();

        if (!$this->isModuleEnabled($websiteId) || !$this->config->sendWelcomeMessage($websiteId)) {
            return false;
        }

        $customerData = $customer->getDataModel();
        $messageRecipient = $this->getCustomerMobilePhoneNumber($customerData);

        if ($messageRecipient === null) {
            return false;
        }

        $messageTemplate = $this->config->getWelcomeMessageTemplate((int)$customer->getStoreId());

        $this->messageService->setCustomer($customerData);

        return $this->messageService->sendMessage($messageTemplate, $messageRecipient, 'customer');
    }
}
