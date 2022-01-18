<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications 
 *
 * @package Mobtexting\SMSNotifications\Block\Customer\Account\Create
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Block\Customer\Account\Create;

use Mobtexting\SMSNotifications\Block\AbstractBlock;

/**
 * Subscribe Form Field Block
 *
 * @package Mobtexting\SMSNotifications\Block\Customer\Account\Create
 */
class SubscribeField extends AbstractBlock
{
    public function isOptinRequired(): bool
    {
        return $this->config->isOptinRequired($this->getWebsiteId());
    }

    public function isTermsAndConditionsShownAfterOptin(): bool
    {
        return $this->config->isTermsAndConditionsShownAfterOptin($this->getWebsiteId());
    }
}
