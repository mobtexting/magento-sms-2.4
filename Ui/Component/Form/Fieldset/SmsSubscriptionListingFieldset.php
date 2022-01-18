<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications
 *
 * @package Mobtexting\SMSNotifications\Ui\Component\Form\Fieldset
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Ui\Component\Form\Fieldset;

use Mobtexting\SMSNotifications\Api\ConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Form\Fieldset;

/**
 * SMS Subscription Listing Fieldset UI Component
 *
 * @package Mobtexting\SMSNotifications\Ui\Component\Form\Fieldset
 */
class SmsSubscriptionListingFieldset extends Fieldset
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
        ContextInterface $context,
        StoreManagerInterface $storeManager,
        ConfigInterface $config,
        $components = [],
        array $data = []
    ) {
        parent::__construct($context, $components, $data);

        $this->storeManager = $storeManager;
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function prepare()
    {
        parent::prepare();

        try {
            $websiteId = (int)$this->storeManager->getStore()->getWebsiteId();
        } catch (NoSuchEntityException $e) {
            $websiteId = null;
        }

        if (!$this->config->isEnabled($websiteId)) {
            $this->_data['config']['componentDisabled'] = true;
        }
    }
}
