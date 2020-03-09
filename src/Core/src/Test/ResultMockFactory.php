<?php

declare(strict_types=1);

namespace AsyncAws\Core\Test;

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
