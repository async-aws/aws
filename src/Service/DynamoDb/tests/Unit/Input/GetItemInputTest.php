<?php

namespace AsyncAws\DynamoDb\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\Input\GetItemInput;

class GetItemInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetItemInput([
            'TableName' => 'Music',
            'Key' => [
                'Artist' => ['S' => 'Acme Band'],
                'SongTitle' => ['S' => 'Happy Day'],
            ],
            'ConsistentRead' => true,
            'ReturnConsumedCapacity' => 'TOTAL',
            'ProjectionExpression' => 'LastPostDateTime, Message, Tags',
        ]);

        // see example-1.json from SDK
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.0
X-AMZ-Target: DynamoDB_20120810.GetItem

{
    "TableName": "Music",
    "Key": {
        "Artist": {
            "S": "Acme Band"
        },
        "SongTitle": {
            "S": "Happy Day"
        }
    },
    "ConsistentRead": true,
    "ReturnConsumedCapacity": "TOTAL",
    "ProjectionExpression":"LastPostDateTime, Message, Tags"
}
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
