<?php

namespace AsyncAws\SecretsManager\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\SecretsManager\Result\UpdateSecretResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class UpdateSecretResponseTest extends TestCase
{
    public function testUpdateSecretResponse(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "ARN": "arn:aws:secretsmanager:us-west-2:123456789012:secret:MyTestDatabaseSecret-a1b2c3",
            "Name": "MyTestDatabaseSecret",
            "VersionId": "EXAMPLE1-90ab-cdef-fedc-ba987SECRET1"
        }');

        $client = new MockHttpClient($response);
        $result = new UpdateSecretResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('arn:aws:secretsmanager:us-west-2:123456789012:secret:MyTestDatabaseSecret-a1b2c3', $result->getARN());
        self::assertSame('MyTestDatabaseSecret', $result->getName());
        self::assertSame('EXAMPLE1-90ab-cdef-fedc-ba987SECRET1', $result->getVersionId());
    }
}
