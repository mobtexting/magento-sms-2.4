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

use Mobtexting\SMSNotifications\Api\ConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Terms & Conditions View Model
 *
 * @package Mobtexting\SMSNotifications\ViewModel
 */
class TermsConditions implements ArgumentInterface
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var \Mobtexting\SMSNotifications\Api\ConfigInterface
     */
    private $config;

    public function __construct(StoreManagerInterface $storeManager, ConfigInterface $config)
    {
        $this->storeManager = $storeManager;
        $this->config = $config;
    }

    public function getContent(): string
    {
        try {
            $websiteId = (int)$this->storeManager->getStore()->getWebsiteId();
        } catch (NoSuchEntityException $e) {
            $websiteId = null;
        }

        return $this->config->getTermsAndConditions($websiteId) ?? '';
    }
}
