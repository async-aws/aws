<?php

namespace AsyncAws\DynamoDb\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\Input\DeleteItemInput;

class DeleteItemInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DeleteItemInput([
            'TableName' => 'Music',
            'Key' => [
                'Artist' => ['S' => 'No One You Know'],
                'SongTitle' => ['S' => 'Scared of My Shadow'],
            ],
            'ReturnValues' => 'ALL_OLD',
            'ConditionExpression' => 'attribute_not_exists(Replies)',
        ]);

        // see example-1.json from SDK
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.0
X-AMZ-Target: DynamoDB_20120810.DeleteItem

{
    "Key": {
        "Artist": {
            "S": "No One You Know"
        },
        "SongTitle": {
            "S": "Scared of My Shadow"
        }
    },
    "TableName": "Music",
    "ConditionExpression": "attribute_not_exists(Replies)",
    "ReturnValues": "ALL_OLD"
}
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
