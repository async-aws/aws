<?php

namespace AsyncAws\DynamoDb\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\Result\ScanOutput;
use Symfony\Component\HttpClient\MockHttpClient;

class ScanOutputTest extends TestCase
{
    public function testScanOutput(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "ConsumedCapacity": [],
            "Count": 2,
            "Items": [
                {
                    "AlbumTitle": {
                        "S": "Somewhat Famous"
                    },
                    "SongTitle": {
                        "S": "Call Me Today"
                    }
                },
                {
                    "AlbumTitle": {
                        "S": "Blue Sky Blues"
                    },
                    "SongTitle": {
                        "S": "Scared of My Shadow"
                    }
                }
            ],
            "ScannedCount": 3
        }');

        $client = new MockHttpClient($response);
        $result = new ScanOutput($client->request('POST', 'http://localhost'), $client);

        $items = $result->getItems(true);
        foreach ($items as $name => $item) {
            self::assertCount(2, $item);
            self::assertArrayHasKey('AlbumTitle', $item);
            self::assertEquals('Somewhat Famous', $item['AlbumTitle']->getS());
            self::assertArrayHasKey('SongTitle', $item);
            self::assertEquals('Call Me Today', $item['SongTitle']->getS());

            break;
        }

        self::assertCount(2, $items);
        self::assertSame(2, $result->getCount());
        self::assertSame(3, $result->getScannedCount());
    }
}
