<?php

namespace AsyncAws\SecretsManager\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\SecretsManager\Input\ListSecretsRequest;
use AsyncAws\SecretsManager\Result\ListSecretsResponse;
use AsyncAws\SecretsManager\SecretsManagerClient;
use AsyncAws\SecretsManager\ValueObject\SecretListEntry;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListSecretsResponseTest extends TestCase
{
    public function testListSecretsResponse(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "SecretList": [
                {
                    "ARN": "arn:aws:secretsmanager:us-west-2:123456789012:secret:MyTestDatabaseSecret-a1b2c3",
                    "Description": "My test database secret",
                    "LastChangedDate": 1523477145.729,
                    "Name": "MyTestDatabaseSecret",
                    "SecretVersionsToStages": {
                        "EXAMPLE1-90ab-cdef-fedc-ba987EXAMPLE": [
                            "AWSCURRENT"
                        ]
                    }
                },
                {
                    "ARN": "arn:aws:secretsmanager:us-west-2:123456789012:secret:MyTestDatabaseSecret1-d4e5f6",
                    "Description": "Another secret created for a different database",
                    "LastChangedDate": 1523482025.685,
                    "Name": "MyTestDatabaseSecret1",
                    "SecretVersionsToStages": {
                        "EXAMPLE2-90ab-cdef-fedc-ba987EXAMPLE": [
                            "AWSCURRENT"
                        ]
                    }
                }
            ]
        }');

        $client = new MockHttpClient($response);
        $result = new ListSecretsResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new SecretsManagerClient(), new ListSecretsRequest([]));

        /** @var SecretListEntry[] $secretList */
        $secretList = iterator_to_array($result->getIterator());

        self::assertCount(2, $secretList);

        self::assertSame('arn:aws:secretsmanager:us-west-2:123456789012:secret:MyTestDatabaseSecret-a1b2c3', $secretList[0]->getArn());
        self::assertSame('My test database secret', $secretList[0]->getDescription());
        self::assertSame('MyTestDatabaseSecret', $secretList[0]->getName());
        $versionToStages = $secretList[0]->getSecretVersionsToStages();

        self::assertArrayHasKey('EXAMPLE1-90ab-cdef-fedc-ba987EXAMPLE', $versionToStages);
        self::assertSame('AWSCURRENT', $versionToStages['EXAMPLE1-90ab-cdef-fedc-ba987EXAMPLE'][0]);
    }
}
