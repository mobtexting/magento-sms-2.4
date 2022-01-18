<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications
 *
 * @package Mobtexting\SMSNotifications\Controller\SmsNotifications
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Controller\SmsNotifications;

use Magento\Customer\Controller\AbstractAccount;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Store\Model\StoreManagerInterface;
use Mobtexting\SMSNotifications\Model\Config;

/**
 * Manage SMS Subscriptions Controller
 *
 * @package Mobtexting\SMSNotifications\Controller\SmsNotifications
 */
class Manage extends AbstractAccount
{
    /**
     * @var \Magento\Store\Api\StoreManagementInterface
     */
    private $storeManager;
    /**
     * @var \Mobtexting\SMSNotifications\Model\Config
     */
    private $config;

    public function __construct(Context $context, StoreManagerInterface $storeManager, Config $config)
    {
        parent::__construct($context);

        $this->storeManager = $storeManager;
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        try {
            $websiteId = (int)$this->storeManager->getStore()->getWebsiteId();
        } catch (NoSuchEntityException $e) {
            $websiteId = null;
        }

        if (!$this->config->isEnabled($websiteId)) {
            throw new NotFoundException(__('SMS Notifications is not enabled.'));
        }

        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}
