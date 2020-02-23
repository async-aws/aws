<?php

declare(strict_types=1);

namespace AsyncAws\Test\Unit\Symfony;

use AsyncAws\Core\AwsClient;
use AsyncAws\Symfony\Bundle\DependencyInjection\AwsPackagesProvider;
use AsyncAws\Test\ServiceProvider;
use PHPUnit\Framework\TestCase;

class AwsPackagesProviderTest extends TestCase
{
    public function testAllPackagesExists()
    {
        $packages = AwsPackagesProvider::getServiceNames();

        foreach (ServiceProvider::getAwsServiceNames() as $service) {
            $name = lcfirst($service);
            $this->assertTrue(in_array($name, $packages), sprintf('The "%s" service should exist in "%s"', $name, AwsPackagesProvider::class));
        }
    }
}
