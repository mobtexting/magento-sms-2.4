<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications
 *
 * @package Mobtexting\SMSNotifications\Model\Config\Backend
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Model\Config\Backend;

use Mobtexting\SMSNotifications\Api\ValidatorInterface;
use Mobtexting\SMSNotifications\Model\SourceValidator;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

/**
 * Source Configuration Field Backend Model
 *
 * @package Mobtexting\SMSNotifications\Model\Config\Backend
 */
class Source extends Value
{
    /**
     * @var \Mobtexting\SMSNotifications\Api\ValidatorInterface
     */
    private $validator;

    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        ValidatorInterface $validator,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        if (!$validator instanceof SourceValidator) {
            throw new \InvalidArgumentException((string)__('Validator must be an instance of SourceValidator.'));
        }

        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);

        $this->validator = $validator;
    }

    public function getSource(): string
    {
        return $this->getValue();
    }

    /**
     * {@inheritdoc}
     * @throws \Zend_Validate_Exception
     */
    protected function _getValidationRulesBeforeSave()
    {
        $sourceType = $this->getFieldsetDataValue('source_type') ?? 'MSISDN';

        $this->validator->setSourceType($sourceType);

        return $this->validator->getValidator();
    }
}
