<?php

namespace AsyncAws\Firehose\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Firehose\Result\PutRecordOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class PutRecordOutputTest extends TestCase
{
    public function testPutRecordOutput(): void
    {
        // see https://docs.aws.amazon.com/firehose/latest/APIReference/API_PutRecord.html
        $response = new SimpleMockedResponse('{
            "Encrypted": false,
            "RecordId": "a"
        }');

        $client = new MockHttpClient($response);
        $result = new PutRecordOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('a', $result->getRecordId());
        self::assertFalse($result->getEncrypted());
    }
}
