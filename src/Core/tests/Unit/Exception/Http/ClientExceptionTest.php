<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit\Exception\Http;

use AsyncAws\Core\AwsError\AwsError;
use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use PHPUnit\Framework\TestCase;

class ClientExceptionTest extends TestCase
{
    public function testException()
    {
        $response = new SimpleMockedResponse('content', ['content-type' => 'application/json'], 400);
        $awsError = new AwsError('code', 'message', 'type', 'detail');
        $exception = new ClientException($response, $awsError);

        self::assertEquals(400, $exception->getCode());
        self::assertEquals('message', $exception->getAwsMessage());
        self::assertEquals('code', $exception->getAwsCode());
        self::assertEquals('type', $exception->getAwsType());
        self::assertEquals('detail', $exception->getAwsDetail());
        self::assertStringContainsString('HTTP 400 returned for "info: url".', $exception->getMessage());
        self::assertStringContainsString('Message: message', $exception->getMessage());
    }
}
