<?php
/**
 * Mobtexting SMS Notifications 
 *
 * Sends transactional SMS notifications 
 *
 * @package Mobtexting\SMSNotifications\Gateway\Hydrator\Strategy
 */

declare(strict_types=1);

namespace Mobtexting\SMSNotifications\Gateway\Hydrator\Strategy;

use Zend\Hydrator\Exception\InvalidArgumentException;
use Zend\Hydrator\Strategy\StrategyInterface;

/**
 * ENUM Hydrator Strategy
 *
 * @package Mobtexting\SMSNotifications\Gateway\Hydrator\Strategy
 */
class Enum implements StrategyInterface
{
    /**
     * @var string
     */
    private $enumClass;

    public function __construct(string $enumClass)
    {
        if (!\is_subclass_of($enumClass, \MyCLabs\Enum\Enum::class)) {
            throw new InvalidArgumentException('Parameter enumClass must be an instance of \MyCLabs\Enum\Enum.');
        }

        $this->enumClass = $enumClass;
    }

    /**
     * {@inheritdoc}
     */
    public function extract($value)
    {
        if ($value === null) {
            return $value;
        }

        if (!$value instanceof \MyCLabs\Enum\Enum) {
            throw new InvalidArgumentException(\sprintf(
                'Unable to extract. Expected instance of \MyCLabs\Enum\Enum. %s was given.',
                is_object($value) ? get_class($value) : gettype($value)
            ));
        }

        return $value->__toString();
    }

    /**
     * {@inheritdoc}
     */
    public function hydrate($value)
    {
        try {
            $enum = new $this->enumClass($value);
        } catch (\UnexpectedValueException $e) {
            throw new InvalidArgumentException(
                \sprintf('Unable to hydrate. Received error: %s', $e->getMessage()),
                0,
                $e
            );
        }

        return $enum;
    }
}
