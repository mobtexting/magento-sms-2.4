<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications
 * 
 * @package Mobtexting\SMSNotifications\ViewModel
 * 
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\ViewModel;

use Mobtexting\SMSNotifications\Model\ResourceModel\TelephonePrefix\CollectionFactory as TelephonePrefixCollectionFactory;
use Magento\Customer\Model\Customer;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Telephone Prefixes View Model
 *
 * @package Mobtexting\SMSNotifications\ViewModel
 */
class TelephonePrefixes implements ArgumentInterface
{
    /**
     * @var \Magento\Directory\Helper\Data
     */
    private $directoryHelper;
    /**
     * @var \Magento\Eav\Api\AttributeRepositoryInterface
     */
    private $attributeRepository;
    /**
     * @var \Mobtexting\SMSNotifications\Model\ResourceModel\TelephonePrefix\CollectionFactory
     */
    private $telephonePrefixCollectionFactory;

    public function __construct(
        DirectoryHelper $directoryHelper,
        AttributeRepositoryInterface $attributeRepository,
        TelephonePrefixCollectionFactory $telephonePrefixCollectionFactory
    ) {
        $this->directoryHelper = $directoryHelper;
        $this->attributeRepository = $attributeRepository;
        $this->telephonePrefixCollectionFactory = $telephonePrefixCollectionFactory;
    }

    public function getOptions(): array
    {
        try {
            $options = $this->attributeRepository->get(Customer::ENTITY, 'sms_mobile_phone_prefix')
                ->getSource()
                ->getAllOptions(false);
        } catch (NoSuchEntityException | LocalizedException $e) {
            $options = [];
        }

        return $options;
    }

    public function getDefaultPrefix(): string
    {
        /** @var \Mobtexting\SMSNotifications\Model\TelephonePrefix $prefix */
        $prefix = $this->telephonePrefixCollectionFactory->create()
            ->addFieldToFilter('country_code', ['eq' => $this->directoryHelper->getDefaultCountry()])
            ->setPageSize(1)
            ->getFirstItem();

        if ($prefix->getId() === null) {
            return '';
        }

        return $prefix->getCountryCode() . '_' . $prefix->getPrefix();
    }
}
