<?php

namespace AsyncAws\Kinesis\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Result\PutRecordsOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class PutRecordsOutputTest extends TestCase
{
    public function testPutRecordsOutput(): void
    {
        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_PutRecords.html
        $response = new SimpleMockedResponse('{
    "FailedRecordCount": 2,
    "Records": [
        {
            "SequenceNumber": "49543463076548007577105092703039560359975228518395012686",
            "ShardId": "shardId-000000000000"
        },
        {
            "ErrorCode": "ProvisionedThroughputExceededException",
            "ErrorMessage": "Rate exceeded for shard shardId-000000000001 in stream exampleStreamName under account 111111111111."
        },
        {
            "ErrorCode": "InternalFailure",
            "ErrorMessage": "Internal service failure."
        }
    ]
}');

        $client = new MockHttpClient($response);
        $result = new PutRecordsOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame(2, $result->getFailedRecordCount());
        self::assertCount(3, $result->getRecords());
        self::assertSame('shardId-000000000000', $result->getRecords()[0]->getShardId());
        self::assertSame('ProvisionedThroughputExceededException', $result->getRecords()[1]->getErrorCode());
    }
}
