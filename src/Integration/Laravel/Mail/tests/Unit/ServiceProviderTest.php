<?php

declare(strict_types=1);

namespace AsyncAws\Illuminate\Mail\Tests\Unit;

use AsyncAws\Illuminate\Mail\ServiceProvider;
use AsyncAws\Illuminate\Mail\Transport\AsyncAwsSesTransport;
use PHPUnit\Framework\TestCase;

class ServiceProviderTest extends TestCase
{
    public function testCreateTransport()
    {
        $serviceProvider = new ServiceProvider('app');
        $transport = $serviceProvider->createTransport([
            'key' => 'my_key',
            'secret' => 'my_secret',
        ]);

        self::assertInstanceOf(AsyncAwsSesTransport::class, $transport);
        $client = $transport->ses();
        $config = $client->getConfiguration();
        self::assertEquals('my_key', $config->get('accessKeyId'));
        self::assertEquals('my_secret', $config->get('accessKeySecret'));
    }

    public function testCreateTransportWithoutConfig()
    {
        $serviceProvider = new ServiceProvider('app');
        $transport = $serviceProvider->createTransport([]);
        self::assertInstanceOf(AsyncAwsSesTransport::class, $transport);
    }
}
