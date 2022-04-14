<?php

namespace AsyncAws\TimestreamWrite\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\TimestreamWrite\Result\WriteRecordsResponse;
use AsyncAws\TimestreamWrite\ValueObject\RecordsIngested;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class WriteRecordsResponseTest extends TestCase
{
    public function testWriteRecordsResponse(): void
    {
        // see https://docs.aws.amazon.com/timestream/latest/developerguide/API_WriteRecords.html
        $response = new SimpleMockedResponse('{
            "RecordsIngested": { 
                "MagneticStore": 1,
                "MemoryStore": 5,
                "Total": 6
            }
        }');

        $client = new MockHttpClient($response);
        $result = new WriteRecordsResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertInstanceOf(RecordsIngested::class, $result->getRecordsIngested());
        self::assertSame(1, $result->getRecordsIngested()->getMagneticStore());
        self::assertSame(5, $result->getRecordsIngested()->getMemoryStore());
        self::assertSame(6, $result->getRecordsIngested()->getTotal());
    }
}
