<?php

declare(strict_types=1);

namespace AsyncAws\Test\Unit\Core;

use AsyncAws\Core\AwsClientFactory;
use AsyncAws\Test\ServiceProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class AwsClientFactoryTest extends TestCase
{
    public function testMethodExists()
    {
        $awsClient = new AwsClientFactory([], null, new MockHttpClient());
        foreach (ServiceProvider::getAwsServices() as $serviceName => $serviceData) {
            $method = lcfirst($serviceName);
            self::assertTrue(method_exists(AwsClientFactory::class, $method), sprintf('The "%s" should have a function named "%s"', AwsClientFactory::class, $method));

            $result = $awsClient->$method();
            $namespace = $serviceData['namespace'] ?? sprintf('AsyncAws\\%s', $serviceName);
            $fqcn = sprintf('%s\\%sClient', $namespace, $serviceName);
            self::assertInstanceOf($fqcn, $result, sprintf('Calling "%s::%s" should return a "%s". Instance of "%s" given.', AwsClientFactory::class, $method, $fqcn, \get_class($result)));
        }
    }
}
