<?php

declare(strict_types=1);

namespace AsyncAws\Illuminate\Queue\Tests\Unit\Connector;

use AsyncAws\Illuminate\Queue\AsyncAwsSqsQueue;
use AsyncAws\Illuminate\Queue\Connector\AsyncAwsSqsConnector;
use PHPUnit\Framework\TestCase;

class AsyncAwsSqsConnectorTest extends TestCase
{
    public function testConnect()
    {
        $connector = new AsyncAwsSqsConnector();
        $queue = $connector->connect([
            'key' => 'my_key',
            'secret' => 'my_secret',
            'queue' => 'https://sqs.us-east-2.amazonaws.com/123456789012/invoice',
        ]);

        self::assertInstanceOf(AsyncAwsSqsQueue::class, $queue);
        $client = $queue->getSqs();
        $config = $client->getConfiguration();
        self::assertEquals('my_key', $config->get('accessKeyId'));
        self::assertEquals('my_secret', $config->get('accessKeySecret'));
        self::assertEquals('https://sqs.us-east-2.amazonaws.com/123456789012/invoice', $queue->getQueue());
    }

    public function testConnectWithMinimumConfig()
    {
        $connector = new AsyncAwsSqsConnector();
        $queue = $connector->connect([
            'queue' => 'https://sqs.us-east-2.amazonaws.com/123456789012/invoice',
        ]);
        self::assertInstanceOf(AsyncAwsSqsQueue::class, $queue);
    }
}
