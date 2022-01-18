<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications 
 *
 * @package Mobtexting\SMSNotifications\Model\MessageVariables
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Model\MessageVariables;

use Mobtexting\SMSNotifications\Api\MessageVariablesInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\GroupRepository as StoreGroupRepository;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Customer Variables
 *
 * @package Mobtexting\SMSNotifications\Model\MessageVariables
 */
class CustomerVariables implements MessageVariablesInterface
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var \Magento\Customer\Api\Data\CustomerInterface
     */
    private $customer;
    /**
     * @var \Magento\Store\Model\GroupRepository
     */
    private $storeGoupRepository;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        StoreGroupRepository $storeGroupRepository
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->storeGoupRepository = $storeGroupRepository;
    }

    public function setCustomer(CustomerInterface $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getVariables(): array
    {
        if ($this->customer === null) {
            return [];
        }

        return [
            'customer_name' => $this->customer->getFirstname() . ' ' . $this->customer->getLastname(),
            'customer_first_name' => $this->customer->getFirstname(),
            'customer_middle_name' => $this->customer->getFirstname(),
            'customer_last_name' => $this->customer->getLastname(),
            'customer_dob' => $this->customer->getDob(),
            'customer_prefix' => $this->customer->getPrefix(),
            'customer_suffix' => $this->customer->getSuffix(),
            'customer_email' => $this->customer->getEmail(),
            'store_name' => $this->getStoreNameById((int)$this->customer->getStoreId())
        ];
    }

    private function getStoreNameById(int $storeId): ?string
    {
        try {
            $storeName = $this->scopeConfig->getValue(
                'general/store_information/name',
                ScopeInterface::SCOPE_STORE,
                $storeId
            );
        } catch (\Exception $e) {
            $storeName = null;
        }

        if ($storeName === null) {
            try {
                $storeName = $this->storeGoupRepository->get($this->storeManager->getStore()->getStoreGroupId())
                    ->getName();
            } catch (NoSuchEntityException $e) {
                $storeName = null;
            }
        }

        return $storeName;
    }
}
