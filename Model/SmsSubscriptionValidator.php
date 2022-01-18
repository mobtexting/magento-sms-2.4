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
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Validator\DataObject as ValidatorObject;
use Magento\Framework\Validator\DataObjectFactory as ValidatorObjectFactory;

/**
 * SMS Subscription Model Validator
 *
 * @package Mobtexting\SMSNotifications\Model
 */
class SmsSubscriptionValidator extends Validator
{
    public function __construct(
        ValidatorObjectFactory $validatorObjectFactory,
        ValidationRulesInterface $validationRules
    ) {
        if (!$validationRules instanceof SmsSubscriptionValidationRules) {
            throw new \InvalidArgumentException(
                (string)__('Validation Rules object must be an instance of SmsSubscriptionValidationRules.')
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
        if (!$model instanceof SmsSubscription) {
            throw new \InvalidArgumentException(
                (string)__('Model to validate must be an instance of SmsSubscription.')
            );
        }

        parent::validate($model);
    }

    /**
     * @throws \Zend_Validate_Exception
     */
    protected function addValidationRules(ValidatorObject $validator): void
    {
        $this->validationRules->addRequiredFieldRules($validator);
        $this->validationRules->addSmsTypeIsValidRule($validator);
    }
}
