<?php

declare(strict_types=1);

namespace AsyncAws\Core\Test;

use AsyncAws\Core\Result;

/**
 * An easy way to create Result objects for your tests.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class ResultMockFactory
{
    /**
     * Instantiate a Result class with some data.
     *
     * <code>
     * ResultMockFactory::create(SendEmailResponse::class, ['MessageId'=>'foo123']);
     * </code>
     *
     * @template T
     * @psalm-param class-string<T> $class
     *
     * @return T
     */
    public static function create(string $class, array $data = [])
    {
        $parent = get_parent_class($class);
        if (false === $parent || Result::class !== $parent) {
            throw new \LogicException(sprintf('The "%s::%s" can only be used for classes that extend "%s"', __CLASS__, __METHOD__, Result::class));
        }

        $rereflectionClass = new \ReflectionClass($class);
        $object = $rereflectionClass->newInstanceWithoutConstructor();
        $data['initialized'] = true;

        foreach ($data as $propertyName => $propertyValue) {
            $property = $rereflectionClass->getProperty($propertyName);
            $property->setAccessible(true);
            $property->setValue($object, $propertyValue);
        }

        self::addUndefinedProperties($rereflectionClass, $object, $data);

        return $object;
    }

    /**
     * Try to add some values to the properties not defined in $data.
     *
     * @throws \ReflectionException
     */
    private static function addUndefinedProperties(\ReflectionClass $rereflectionClass, $object, array $data): void
    {
        foreach ($rereflectionClass->getProperties(\ReflectionProperty::IS_PRIVATE) as $property) {
            if (\array_key_exists($property->getName(), $data)) {
                continue;
            }

            $getter = $rereflectionClass->getMethod('get' . $property->getName());
            /** @psalm-suppress PossiblyNullReference */
            if (!$getter->hasReturnType() || $getter->getReturnType()->allowsNull()) {
                continue;
            }

            /** @psalm-suppress PossiblyNullReference */
            switch ($getter->getReturnType()->getName()) {
                case 'int':
                    $propertyValue = 0;

                    break;
                case 'string':
                    $propertyValue = '';

                    break;
                case 'bool':
                    $propertyValue = false;

                    break;
                case 'float':
                    $propertyValue = 0.0;

                    break;
                case 'array':
                    $propertyValue = [];

                    break;
                default:
                    $propertyValue = null;

                    break;
            }

            if (null !== $propertyValue) {
                $property->setAccessible(true);
                $property->setValue($object, $propertyValue);
            }
        }
    }
}
