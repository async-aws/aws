<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Integration;

use AsyncAws\Core\Exception\Http\NetworkException;
use AsyncAws\S3\S3Client;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;

class WaiterTest extends TestCase
{
    public function testWaiterException(): void
    {
        if (!class_exists(S3Client::class)) {
            self::markTestSkipped('This test needs a client with waiter endpoints');
        }

        $client = new S3Client(['endpoint' => 'http://127.0.0.1:10'], null, HttpClient::create(['timeout' => 5]));
        $result = $client->bucketExists(['Bucket' => 'test']);

        $this->expectException(NetworkException::class);
        $result->isSuccess();
    }

    public function testWaiterThrowOnce(): void
    {
        if (!class_exists(S3Client::class)) {
            self::markTestSkipped('This test needs a client with waiter endpoints');
        }

        $client = new S3Client(['endpoint' => 'http://127.0.0.1:10'], null, HttpClient::create(['timeout' => 5]));
        $result = $client->bucketExists(['Bucket' => 'test']);

        try {
            $result->isSuccess();
            self::fail('An exception should have been triggered');
        } catch (NetworkException $e) {
        }

        unset($result);
        self::expectNotToPerformAssertions();
    }

    public function testWaiterThrowOnRetry(): void
    {
        if (!class_exists(S3Client::class)) {
            self::markTestSkipped('This test needs a client with waiter endpoints');
        }

        $client = new S3Client(['endpoint' => 'http://127.0.0.1:10'], null, HttpClient::create(['timeout' => 5]));
        $result = $client->bucketExists(['Bucket' => 'test']);

        try {
            $result->isSuccess();
            self::fail('An exception should have been triggered');
        } catch (NetworkException $e) {
        }

        $this->expectException(NetworkException::class);
        $result->isSuccess();
    }

    public function testWaiterThrowOnWait(): void
    {
        if (!class_exists(S3Client::class)) {
            self::markTestSkipped('This test needs a client with waiter endpoints');
        }

        $client = new S3Client(['endpoint' => 'http://127.0.0.1:10'], null, HttpClient::create(['timeout' => 5]));
        $result = $client->bucketExists(['Bucket' => 'test']);

        try {
            $result->isSuccess();
            self::fail('An exception should have been triggered');
        } catch (NetworkException $e) {
        }

        $this->expectException(NetworkException::class);
        $result->wait();
    }
}
