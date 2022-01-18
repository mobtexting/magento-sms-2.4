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
use Mobtexting\SMSNotifications\Api\ValidatorInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Validator\DataObject as ValidatorObject;
use Magento\Framework\Validator\DataObjectFactory as ValidatorObjectFactory;

/**
 * Base Model Validator
 *
 * @package Mobtexting\SMSNotifications\Model
 * @api
 */
abstract class Validator implements ValidatorInterface
{
    /**
     * @var \Magento\Framework\Validator\DataObjectFactory
     */
    protected $validatorObjectFactory;
    /**
     * @var \Mobtexting\SMSNotifications\Api\ValidationRulesInterface
     */
    protected $validationRules;
    protected $isValid = true;
    /**
     * @var string[]
     */
    protected $messages = [];

    public function __construct(
        ValidatorObjectFactory $validatorObjectFactory,
        ValidationRulesInterface $validationRules
    ) {
        $this->validatorObjectFactory = $validatorObjectFactory;
        $this->validationRules = $validationRules;
    }

    /**
     * @throws \Exception
     * @throws \Zend_Validate_Exception
     */
    public function validate(AbstractModel $model): void
    {
        /** @var \Magento\Framework\Validator\DataObject $validator */
        $validator = $this->getValidator();

        if (!$validator->isValid($model)) {
            $this->messages = $validator->getMessages();
            $this->isValid = false;
        }
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @throws \Zend_Validate_Exception
     */
    public function getValidator(): \Zend_Validate_Interface
    {
        /** @var \Magento\Framework\Validator\DataObject $validator */
        $validator = $this->validatorObjectFactory->create();

        $this->addValidationRules($validator);

        return $validator;
    }

    abstract protected function addValidationRules(ValidatorObject $validator): void;
}
