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
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_PutRecords.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new PutRecordsOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame(1337, $result->getFailedRecordCount());
        // self::assertTODO(expected, $result->getRecords());
        self::assertSame('changeIt', $result->getEncryptionType());
    }
}
