<?php
/**
 * Mobtexting SMS Notificationss
 * 
 * Sends transactional SMS notifications
 *
 * @package Mobtexting\SMSNotifications\Model
 * 
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Model;

use Mobtexting\SMSNotifications\Api\Data\TelephonePrefixInterface;
use Mobtexting\SMSNotifications\Api\Data\TelephonePrefixInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Mobtexting\SMSNotifications\Model\ResourceModel\TelephonePrefix as TelephonePrefixResource;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Registry;

/**
 * Telephone Prefix Data Model
 *
 * @package Mobtexting\SMSNotifications\Model
 * @author Joseph Leedy <joseph@Mobtexting.com>
 * @method $this setCountryCode(string $countryCode)
 * @method string getCountryCode()
 * @method bool hasCountryCode()
 * @method $this setCountryName(string $countryName)
 * @method int getCountryName()
 * @method bool hasCountryName()
 * @method $this setPrefix(int $prefix)
 * @method int getPrefix()
 * @method bool hasPrefix()
 */
class TelephonePrefix extends AbstractModel
{
    /**
     * {@inheritdoc}
     */
    protected $_eventPrefix = 'sms_notifications_telephone_prefix';
    /**
     * {@inheritdoc}
     */
    protected $_eventObject = 'telephone_prefix';
    /**
     * @var \Magento\Framework\Api\DataObjectHelper
     */
    private $dataObjectHelper;
    /**
     * @var \Magento\Framework\Reflection\DataObjectProcessor
     */
    private $dataObjectProcessor;
    /**
     * @var \Mobtexting\SMSNotifications\Api\Data\TelephonePrefixInterfaceFactory
     */
    private $telephonePrefixFactory;

    public function __construct(
        Context $context,
        Registry $registry,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        TelephonePrefixInterfaceFactory $telephonePrefixFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);

        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->telephonePrefixFactory = $telephonePrefixFactory;
    }

    public function getDataModel(): TelephonePrefixInterface
    {
        $telephonePrefixData = $this->getData();
        /** @var \Mobtexting\SMSNotifications\Api\Data\TelephonePrefixInterface $telephonePrefix */
        $telephonePrefix = $this->telephonePrefixFactory->create();

        $this->dataObjectHelper->populateWithArray(
            $telephonePrefix,
            $telephonePrefixData,
            TelephonePrefixInterface::class
        );

        return $telephonePrefix;
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(TelephonePrefixResource::class);
        $this->setIdFieldName('country_code');
    }
}
