<?php

namespace AsyncAws\DynamoDb\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\Result\UpdateItemOutput;
use Symfony\Component\HttpClient\MockHttpClient;

class UpdateItemOutputTest extends TestCase
{
    public function testUpdateItemOutput(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "Attributes": {
                "AlbumTitle": {
                    "S": "Louder Than Ever"
                },
                "Artist": {
                    "S": "Acme Band"
                },
                "SongTitle": {
                    "S": "Happy Day"
                },
                "Year": {
                    "N": "2015"
                }
            }
        }');

        $client = new MockHttpClient($response);
        $result = new UpdateItemOutput(new Response($client->request('POST', 'http://localhost'), $client));

        $attributes = $result->getAttributes();
        self::assertCount(4, $attributes);
        self::assertArrayHasKey('AlbumTitle', $attributes);
        self::assertEquals('Louder Than Ever', $attributes['AlbumTitle']->getS());
        self::assertArrayHasKey('SongTitle', $attributes);
        self::assertEquals('Happy Day', $attributes['SongTitle']->getS());
        self::assertArrayHasKey('Year', $attributes);
        self::assertEquals('2015', $attributes['Year']->getN());
        self::assertNull($attributes['Year']->getS());
    }
}
