<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications
 *
 * @package Mobtexting\SMSNotifications\Block
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Block;

use Mobtexting\SMSNotifications\Api\ConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;

/**
 * SMS Notifications Base Block
 *
 * @package Mobtexting\SMSNotifications\Block
 */
abstract class AbstractBlock extends Template
{
    /**
     * @var \Mobtexting\SMSNotifications\Api\ConfigInterface
     */
    protected $config;

    public function __construct(
        Context $context,
        ConfigInterface $config,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function toHtml()
    {
        if (!$this->config->isEnabled($this->getWebsiteId())) {
            return '';
        }

        return parent::toHtml();
    }

    protected function getWebsiteId(): ?int
    {
        try {
            $websiteId = (int)$this->_storeManager->getStore()->getWebsiteId();
        } catch (NoSuchEntityException $e) {
            $websiteId = null;
        }

        return $websiteId;
    }
}
