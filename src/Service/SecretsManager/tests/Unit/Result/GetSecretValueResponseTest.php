<?php

namespace AsyncAws\SecretsManager\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\SecretsManager\Result\GetSecretValueResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetSecretValueResponseTest extends TestCase
{
    public function testGetSecretValueResponse(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "ARN": "arn:aws:secretsmanager:us-west-2:123456789012:secret:MyTestDatabaseSecret-a1b2c3",
            "CreatedDate": 1523477145.713,
            "Name": "MyTestDatabaseSecret",
            "SecretString": "{\\"username\\":\\"david\\",\\"password\\":\\"BnQw&XDWgaEeT9XGTT29\\"}",
            "VersionId": "EXAMPLE1-90ab-cdef-fedc-ba987SECRET1",
            "VersionStages": [
                "AWSPREVIOUS"
            ]
        }');

        $client = new MockHttpClient($response);
        $result = new GetSecretValueResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('arn:aws:secretsmanager:us-west-2:123456789012:secret:MyTestDatabaseSecret-a1b2c3', $result->getARN());
        self::assertSame('MyTestDatabaseSecret', $result->getName());
        self::assertSame('EXAMPLE1-90ab-cdef-fedc-ba987SECRET1', $result->getVersionId());
        self::assertSame('{"username":"david","password":"BnQw&XDWgaEeT9XGTT29"}', $result->getSecretString());
        self::assertSame('1523477145.713000', $result->getCreatedDate()->format('U.u'));
        $versionStages = $result->getVersionStages();
        self::assertCount(1, $versionStages);
        self::assertSame('AWSPREVIOUS', $versionStages[0]);
    }
}
