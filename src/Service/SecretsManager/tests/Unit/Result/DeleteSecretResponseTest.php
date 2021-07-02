<?php

namespace AsyncAws\SecretsManager\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\SecretsManager\Result\DeleteSecretResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DeleteSecretResponseTest extends TestCase
{
    public function testDeleteSecretResponse(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "ARN": "arn:aws:secretsmanager:us-west-2:123456789012:secret:MyTestDatabaseSecret-a1b2c3",
            "DeletionDate": "1524085349.095",
            "Name": "MyTestDatabaseSecret"
        }');

        $client = new MockHttpClient($response);
        $result = new DeleteSecretResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('arn:aws:secretsmanager:us-west-2:123456789012:secret:MyTestDatabaseSecret-a1b2c3', $result->getARN());
        self::assertSame('MyTestDatabaseSecret', $result->getName());
        self::assertSame('1524085349.095000', $result->getDeletionDate()->format('U.u'));
    }
}
