<?php

namespace AsyncAws\Athena\Tests\Unit\Result;

use AsyncAws\Athena\Result\GetSessionResponse;
use AsyncAws\Athena\ValueObject\EngineConfiguration;
use AsyncAws\Athena\ValueObject\SessionConfiguration;
use AsyncAws\Athena\ValueObject\SessionStatistics;
use AsyncAws\Athena\ValueObject\SessionStatus;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetSessionResponseTest extends TestCase
{
    public function testGetSessionResponse(): void
    {
        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_GetSession.html
        $response = new SimpleMockedResponse('{
           "Description": "iad international async_aws",
           "EngineConfiguration": {
              "AdditionalConfigs": {
                 "contrib" : "iad"
              },
              "CoordinatorDpuSize": 3,
              "DefaultExecutorDpuSize": 1,
              "MaxConcurrentDpus": 200
           },
           "EngineVersion": "PySpark engine version 3",
           "NotebookVersion": "v12",
           "SessionConfiguration": {
              "EncryptionConfiguration": {
                 "EncryptionOption": "SSE_S3",
                 "KmsKey": "iadKey"
              },
              "ExecutionRole": "arn:aws:iam::sandbox-iad:role/test",
              "IdleTimeoutSeconds": 200,
              "WorkingDirectory": "iadDirectory"
           },
           "SessionId": "session-iad-2036",
           "Statistics": {
              "DpuExecutionInMillis": 300
           },
           "Status": {
              "EndDateTime": 1680938068.205,
              "IdleSinceDateTime": 1680938068.0053,
              "LastModifiedDateTime": 1680938068.012,
              "StartDateTime": 1680938068,
              "State": "BUSY",
              "StateChangeReason": "modification"
           },
           "WorkGroup": "iadinternational"
        }');

        $client = new MockHttpClient($response);
        $result = new GetSessionResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('session-iad-2036', $result->getSessionId());
        self::assertSame('iad international async_aws', $result->getDescription());
        self::assertSame('iadinternational', $result->getWorkGroup());
        self::assertSame('PySpark engine version 3', $result->getEngineVersion());
        self::assertInstanceOf(EngineConfiguration::class, $result->getEngineConfiguration());
        self::assertSame('v12', $result->getNotebookVersion());
        self::assertInstanceOf(SessionConfiguration::class, $result->getSessionConfiguration());
        self::assertInstanceOf(SessionStatus::class, $result->getStatus());
        self::assertInstanceOf(SessionStatistics::class, $result->getStatistics());
    }
}
