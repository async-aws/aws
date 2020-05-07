<?php

declare(strict_types=1);

namespace AsyncAws\Illuminate\Queue\Tests\Unit;

use AsyncAws\Illuminate\Queue\Connector\AsyncAwsSqsConnector;
use AsyncAws\Illuminate\Queue\ServiceProvider;
use PHPUnit\Framework\TestCase;

class ServiceProviderTest extends TestCase
{
    public function testCreateTransportWithoutConfig()
    {
        $serviceProvider = new ServiceProvider('app');
        $transport = $serviceProvider->createConnector();
        self::assertInstanceOf(AsyncAwsSqsConnector::class, $transport);
    }
}
