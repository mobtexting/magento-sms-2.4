<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications 
 *
 * @package Mobtexting\SMSNotifications\Logger\Handler
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Logger\Handler;

use Mobtexting\SMSNotifications\Api\ConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem\DriverInterface;
use Magento\Framework\Logger\Handler\Base;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Log File Handler
 *
 * @package Mobtexting\SMSNotifications\Logger\Handler
 */
class FileHandler extends Base
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var \Mobtexting\SMSNotifications\Api\ConfigInterface
     */
    private $config;

    public function __construct(
        DriverInterface $filesystem,
        StoreManagerInterface $storeManager,
        ConfigInterface $config,
        $filePath = null
    ) {
        $this->config = $config;
        $fileName = '/var/log/sms_notifications.log';

        parent::__construct($filesystem, $filePath, $fileName);

        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function isHandling(array $record)
    {
        try {
            $websiteId = (int)$this->storeManager->getStore()->getWebsiteId();
        } catch (NoSuchEntityException $e) {
            $websiteId = null;
        }

        return !(!$this->config->isEnabled($websiteId) || !$this->config->isLoggingEnabled());
    }
}
