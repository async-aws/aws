<?php

namespace AsyncAws\Lambda\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Lambda\Result\InvocationResponse;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class InvocationResponseTest extends TestCase
{
    public function testInvocationResponse(): void
    {
        $json = '{"exitCode":0,"output":"Foobar"}';

        $response = new SimpleMockedResponse($json, ['content-type' => 'application/json'], 200);

        $result = new InvocationResponse($response, new MockHttpClient());

        self::assertEquals($json, $result->getPayload());
        self::assertEquals(200, $result->getStatusCode());
    }
}
