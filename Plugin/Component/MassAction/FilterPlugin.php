<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications
 *
 * @package Mobtexting\SMSNotifications\Plugin\Component\MassAction
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Plugin\Component\MassAction;

use Mobtexting\SMSNotifications\Api\ConfigInterface;
use Mobtexting\SMSNotifications\Model\ResourceModel\SmsSubscription\Collection as SmsSubscriptionCollection;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\MassAction\Filter;

/**
 * Plug-in for Mass Action Filter UI Component
 *
 * Replaces core logic to filter by SMS notification type instead of entity ID.
 *
 * @package Mobtexting\SMSNotifications\Plugin\Component\MassAction
 * @see \Magento\Ui\Component\MassAction\Filter
 */
class FilterPlugin
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;
    /**
     * @var \Magento\Framework\Api\FilterBuilder
     */
    private $filterBuilder;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var \Mobtexting\SMSNotifications\Api\ConfigInterface
     */
    private $config;
    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    private $filter;
    /**
     * @var \Magento\Framework\View\Element\UiComponent\DataProvider\DataProviderInterface
     */
    private $dataProvider;

    public function __construct(
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        StoreManagerInterface $storeManager,
        ConfigInterface $config
    ) {
        $this->request = $request;
        $this->filterBuilder = $filterBuilder;
        $this->storeManager = $storeManager;
        $this->config = $config;
    }

    /**
     * @param \Magento\Ui\Component\MassAction\Filter $subject
     * @param callable $proceed
     * @param \Magento\Framework\Data\Collection\AbstractDb $collection
     * @return \Magento\Framework\Data\Collection\AbstractDb
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function aroundGetCollection(
        Filter $subject,
        callable $proceed,
        AbstractDb $collection
    ) {
        if (!$this->isModuleEnabled() || !($collection instanceof SmsSubscriptionCollection)) {
            return $proceed($collection);
        }

        $this->filter = $subject;
        $selected = $this->request->getParam($subject::SELECTED_PARAM);
        $excluded = $this->request->getParam($subject::EXCLUDED_PARAM);
        $isExcludedIdsValid = is_array($excluded) && count($excluded) > 0;
        $isSelectedIdsValid = is_array($selected) && count($selected) > 0;

        if ($excluded !== 'false') {
            if (!$isExcludedIdsValid && !$isSelectedIdsValid) {
                throw new LocalizedException(
                    __('An SMS notification type must be selected. Please choose at least one and try again.')
                );
            }
        }

        $this->initDataProvider();
        $this->applySelectionOnTargetProvider();

        $collection->addFieldToFilter('sms_type', ['in' => $this->getSmsTypes()]);

        return $collection;
    }

    private function isModuleEnabled(): bool
    {
        try {
            $websiteId = (int)$this->storeManager->getStore()->getWebsiteId();
        } catch (NoSuchEntityException $e) {
            $websiteId = null;
        }

        return $this->config->isEnabled($websiteId);
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function initDataProvider(): void
    {
        if ($this->dataProvider !== null) {
            return;
        }

        $component = $this->filter->getComponent();

        $this->filter->prepareComponent($component);

        $this->dataProvider = $component->getContext()->getDataProvider();
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function applySelectionOnTargetProvider(): void
    {
        $selected = $this->request->getParam($this->filter::SELECTED_PARAM);
        $excluded = $this->request->getParam($this->filter::EXCLUDED_PARAM);

        if ($excluded === 'false') {
            return;
        }

        try {
            if (is_array($excluded) && count($excluded) > 0) {
                $this->filterBuilder->setConditionType('nin')
                    ->setField('sms_type')
                    ->setValue($excluded);

                $this->dataProvider->addFilter($this->filterBuilder->create());
            } elseif (is_array($selected) && count($selected) > 0) {
                $this->filterBuilder->setConditionType('in')
                    ->setField('sms_type')
                    ->setValue($selected);

                $this->dataProvider->addFilter($this->filterBuilder->create());
            }
        } catch (\Exception $e) {
            throw new LocalizedException(__($e->getMessage()));
        }
    }

    /**
     * @return string[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function getSmsTypes(): array
    {
        return $this->dataProvider->getAllSmsTypes();
    }
}
