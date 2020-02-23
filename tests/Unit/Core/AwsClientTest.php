<?php

declare(strict_types=1);

namespace AsyncAws\Test\Unit\Core;

use AsyncAws\Core\AwsClient;
use AsyncAws\Test\ServiceProvider;
use PHPUnit\Framework\TestCase;

class AwsClientTest extends TestCase
{
    public function testMethodExists()
    {
        foreach (ServiceProvider::getAwsServiceNames() as $name) {
            $method = lcfirst($name);
            $this->assertTrue(method_exists(AwsClient::class, $method), sprintf('The "%s" should have a function named "%s"', AwsClient::class, $method));
        }
    }
}
