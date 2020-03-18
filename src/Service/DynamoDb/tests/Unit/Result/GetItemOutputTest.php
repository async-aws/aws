<?php

namespace AsyncAws\DynamoDb\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\Result\GetItemOutput;
use Symfony\Component\HttpClient\MockHttpClient;

class GetItemOutputTest extends TestCase
{
    public function testGetItemOutput(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "Item": {
                "AlbumTitle": {
                    "S": "Songs About Life"
                },
                "Artist": {
                    "S": "Acme Band"
                },
                "SongTitle": {
                    "S": "Happy Day"
                }
            }
        }');

        $client = new MockHttpClient($response);
        $result = new GetItemOutput(new Response($client->request('POST', 'http://localhost'), $client));

        self::assertEquals('Songs About Life', $result->getItem()['AlbumTitle']->getS());
        self::assertEquals('Acme Band', $result->getItem()['Artist']->getS());
        self::assertEquals('Happy Day', $result->getItem()['SongTitle']->getS());
    }
}
