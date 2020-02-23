<?php

declare(strict_types=1);

namespace AsyncAws\Test\Unit\Symfony;

use AsyncAws\Symfony\Bundle\DependencyInjection\AwsPackagesProvider;
use AsyncAws\Test\ServiceProvider;
use PHPUnit\Framework\TestCase;

class AwsPackagesProviderTest extends TestCase
{
    public function testAllPackagesExists()
    {
        $packages = AwsPackagesProvider::getServiceNames();

        foreach (ServiceProvider::getAwsServiceNames() as $service) {
            $name = $this->snakeCase($service);
            self::assertTrue(\in_array($name, $packages), sprintf('The "%s" service should exist in "%s"', $name, AwsPackagesProvider::class));
        }
    }

    /**
     * @see https://stackoverflow.com/a/1993772/1526789
     */
    private function snakeCase(string $input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }

        return implode('_', $ret);
    }
}
