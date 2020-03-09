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
     * @return T
     */
    public static function create(string $class, array $data)
    {
        $parent = get_parent_class($class);
        if (false === $parent || $parent !== Result::class) {
            throw new \LogicException(sprintf('The "%s::%s" can only be used for classes that extend "%s"', __CLASS__, __METHOD__, Result::class));
        }

        $rereflectionClass = new \ReflectionClass($class);
        $object = $rereflectionClass->newInstanceWithoutConstructor();

        foreach ($data as $propertyName => $propertyValue) {
            $property = $rereflectionClass->getProperty($propertyName);
            $property->setAccessible(true);
            $property->setValue($object, $propertyValue);
        }

        return $object;
    }
}
