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
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\UrlInterface as UrlBuilder;
use Magento\Sales\Api\Data\InvoiceInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Invoice Message Variables
 *
 * @package Mobtexting\SMSNotifications\Model\MessageVariables
 * @author Joseph Leedy <joseph@Mobtexting.com>
 */
class InvoiceVariables implements MessageVariablesInterface
{
    /**
     * @var \Magento\Framework\UrlInterface
     */
    private $urlBuilder;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var \Magento\Sales\Api\Data\InvoiceInterface|\Magento\Sales\Model\Order\Invoice
     */
    private $invoice;

    public function __construct(UrlBuilder $urlBuilder, ScopeConfigInterface $scopeConfig)
    {
        $this->urlBuilder = $urlBuilder;
        $this->scopeConfig = $scopeConfig;
    }

    public function getVariables(): array
    {
        if ($this->invoice === null) {
            return [];
        }

        /** @var \Magento\Sales\Api\Data\OrderInterface $order */
        $order = $this->invoice->getOrder();

        return [
            'order_id' => $order->getIncrementId(),
            'order_url' => $this->urlBuilder->getUrl('sales/order/view', ['order_id' => $order->getEntityId()]),
            'invoice_url' => $this->urlBuilder->getUrl('sales/order/invoice', ['order_id' => $order->getEntityId()]),
            'customer_name' => $order->getCustomerFirstname() . ' ' . $order->getCustomerLastname(),
            'customer_first_name' => $order->getCustomerFirstname(),
            'customer_last_name' => $order->getCustomerLastname(),
            'store_name' => $this->getStoreNameById((int)$order->getStoreId(), $order->getStoreName() ?? ''),
        ];
    }

    public function setInvoice(InvoiceInterface $invoice): self
    {
        $this->invoice = $invoice;

        return $this;
    }

    private function getStoreNameById(int $storeId, string $default): string
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
            if (strpos($default, "\n") !== false) {
                $default = explode("\n", $default)[1];
            }

            $storeName = $default;
        }

        return $storeName;
    }
}
