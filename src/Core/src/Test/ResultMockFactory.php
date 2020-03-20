<?php

declare(strict_types=1);

namespace AsyncAws\Core\Test;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * An easy way to create Result objects for your tests.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class ResultMockFactory
{
    /**
     * Instantiate a Result class that throws exception.
     *
     * <code>
     * ResultMockFactory::createFailing(SendEmailResponse::class, 400, 'invalid value');
     * </code>
     *
     * @template T
     * @psalm-param class-string<T> $class
     *
     * @return Result|T
     */
    public static function createFailing(string $class, int $code, ?string $message = null)
    {
        if (Result::class !== $class) {
            $parent = get_parent_class($class);
            if (false === $parent || Result::class !== $parent) {
                throw new \LogicException(sprintf('The "%s::%s" can only be used for classes that extend "%s"', __CLASS__, __METHOD__, Result::class));
            }
        }

        $httpResponse = new SimpleMockedResponse(\json_encode(['message' => $message]), ['content-type' => 'application/json'], $code);
        $client = new MockHttpClient($httpResponse);
        $response = new Response($client->request('POST', 'http://localhost'), $client);

        $reflectionClass = new \ReflectionClass($class);

        return $reflectionClass->newInstance($response);
    }

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
     * @return Result|T
     */
    public static function create(string $class, array $data = [])
    {
        if (Result::class !== $class) {
            $parent = get_parent_class($class);
            if (false === $parent || Result::class !== $parent) {
                throw new \LogicException(sprintf('The "%s::%s" can only be used for classes that extend "%s"', __CLASS__, __METHOD__, Result::class));
            }
        }

        $reflectionClass = new \ReflectionClass(Response::class);
        $response = $reflectionClass->newInstanceWithoutConstructor();
        $property = $reflectionClass->getProperty('resolveResult');
        $property->setAccessible(true);
        $property->setValue($response, true);

        $reflectionClass = new \ReflectionClass($class);
        $object = $reflectionClass->newInstance($response);
        $data['initialized'] = true;

        foreach ($data as $propertyName => $propertyValue) {
            $property = $reflectionClass->getProperty($propertyName);
            $property->setAccessible(true);
            $property->setValue($object, $propertyValue);
        }

        self::addUndefinedProperties($reflectionClass, $object, $data);

        return $object;
    }

    /**
     * Try to add some values to the properties not defined in $data.
     *
     * @throws \ReflectionException
     */
    private static function addUndefinedProperties(\ReflectionClass $reflectionClass, $object, array $data): void
    {
        foreach ($reflectionClass->getProperties(\ReflectionProperty::IS_PRIVATE) as $property) {
            if (\array_key_exists($property->getName(), $data)) {
                continue;
            }

            if (!$reflectionClass->hasMethod('get' . $property->getName())) {
                continue;
            }

            $getter = $reflectionClass->getMethod('get' . $property->getName());
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
