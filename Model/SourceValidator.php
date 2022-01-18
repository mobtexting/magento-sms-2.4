<?php
/**
 * Mobtexting SMS Notifications
 *
 * Sends transactional SMS notifications
 *
 * @package Mobtexting\SMSNotifications\Model
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Model;

use Mobtexting\SMSNotifications\Api\ValidationRulesInterface;
use Mobtexting\SMSNotifications\Model\Config\Backend\Source;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Validator\DataObject as ValidatorObject;
use Magento\Framework\Validator\DataObjectFactory as ValidatorObjectFactory;

/**
 * Source Configuration Field Validator
 *
 * @package Mobtexting\SMSNotifications\Model
 */
class SourceValidator extends Validator
{
    /**
     * @var string
     */
    private $sourceType;

    public function __construct(
        ValidatorObjectFactory $validatorObjectFactory,
        ValidationRulesInterface $validationRules
    ) {
        if (!$validationRules instanceof SourceValidationRules) {
            throw new \InvalidArgumentException(
                (string)__('Validation Rules object must be an instance of SourceValidationRules.')
            );
        }

        parent::__construct($validatorObjectFactory, $validationRules);
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \Exception
     * @throws \Zend_Validate_Exception
     */
    public function validate(AbstractModel $model): void
    {
        if (!$model instanceof Source) {
            throw new \InvalidArgumentException((string)__('Model to validate must be an instance of Source.'));
        }

        parent::validate($model);
    }

    public function setSourceType(string $sourceType): self
    {
        $this->sourceType = $sourceType;

        return $this;
    }

    /**
     * @throws \Zend_Validate_Exception
     */
    protected function addValidationRules(ValidatorObject $validator): void
    {
        $this->validationRules->addSourceIsValidRules($validator, $this->sourceType);
    }
}
