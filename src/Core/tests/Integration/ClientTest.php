<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\S3\S3Client;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function testStreamToStream(): void
    {
        if (!class_exists(S3Client::class)) {
            self::markTestSkipped('This test needs a client with waiter endpoints');
        }

        $client = new S3Client([
            'endpoint' => 'http://localhost:4569',
            'pathStyleEndpoint' => true,
        ], new NullProvider());

        $client->createBucket(['Bucket' => 'foo'])->resolve();
        $client->putObject([
            'Bucket' => 'foo',
            'Key' => 'bar',
            'Body' => 'content',
        ])->resolve();

        $client->putObject([
            'Bucket' => 'foo',
            'Key' => 'bar2',
            'Body' => $client->getObject(['Bucket' => 'foo', 'Key' => 'bar'])->getBody()->getContentAsResource(),
        ])->resolve();

        self::expectNotToPerformAssertions();
    }
}
