<?php

namespace AsyncAws\Firehose\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Firehose\Result\PutRecordBatchOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class PutRecordBatchOutputTest extends TestCase
{
    public function testPutRecordBatchOutput(): void
    {
        // see https://docs.aws.amazon.com/firehose/latest/APIReference/API_PutRecordBatch.html
        $response = new SimpleMockedResponse('{
            "Encrypted": false,
            "FailedPutCount": 0,
            "RequestResponses": [
                {
                    "RecordId": "a"
                },
                {
                    "RecordId": "b"
                }
            ]
        }');

        $client = new MockHttpClient($response);
        $result = new PutRecordBatchOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame(0, $result->getFailedPutCount());
        self::assertFalse($result->getEncrypted());
        self::assertCount(2, $result->getRequestResponses());
        self::assertSame('a', $result->getRequestResponses()[0]->getRecordId());
        self::assertSame('b', $result->getRequestResponses()[1]->getRecordId());
    }
}
