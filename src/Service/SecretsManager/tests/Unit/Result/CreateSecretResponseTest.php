<?php

namespace AsyncAws\SecretsManager\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\SecretsManager\Enum\StatusType;
use AsyncAws\SecretsManager\Result\CreateSecretResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CreateSecretResponseTest extends TestCase
{
    public function testCreateSecretResponse(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "ARN": "arn:aws:secretsmanager:us-west-2:123456789012:secret:MyTestDatabaseSecret-a1b2c3",
            "Name": "MyTestDatabaseSecret",
            "VersionId": "EXAMPLE1-90ab-cdef-fedc-ba987SECRET1",
            "ReplicationStatus": [
                {
                    "KmsKeyId": "string",
                    "LastAccessedDate": 1624887139,
                    "Region": "eu-north-1",
                    "Status": "InSync",
                    "StatusMessage": "Secret with this name already exists in this region"
                }
            ]
        }');

        $client = new MockHttpClient($response);
        $result = new CreateSecretResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('arn:aws:secretsmanager:us-west-2:123456789012:secret:MyTestDatabaseSecret-a1b2c3', $result->getARN());
        self::assertSame('MyTestDatabaseSecret', $result->getName());
        self::assertSame('EXAMPLE1-90ab-cdef-fedc-ba987SECRET1', $result->getVersionId());

        $replicationStatus = $result->getReplicationStatus();
        self::assertCount(1, $replicationStatus);
        self::assertSame($replicationStatus[0]->getRegion(), 'eu-north-1');
        self::assertSame($replicationStatus[0]->getStatus(), StatusType::IN_SYNC);
        self::assertSame($replicationStatus[0]->getKmsKeyId(), 'string');
        self::assertSame($replicationStatus[0]->getStatusMessage(), 'Secret with this name already exists in this region');
        self::assertSame($replicationStatus[0]->getLastAccessedDate()->getTimestamp(), 1624887139);
    }
}
