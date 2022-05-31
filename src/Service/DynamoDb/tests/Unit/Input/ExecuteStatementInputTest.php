<?php

namespace AsyncAws\DynamoDb\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\Input\ExecuteStatementInput;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;

class ExecuteStatementInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ExecuteStatementInput([
            'Statement' => 'SELECT * FROM "Music" WHERE SongTitle = ?',
            'Parameters' => [new AttributeValue([
                'S' => 'Call Me Today',
            ])],
            'ConsistentRead' => false,
            'NextToken' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_ExecuteStatement.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.0
X-AMZ-Target: DynamoDB_20120810.ExecuteStatement

{
    "Statement": "SELECT * FROM \"Music\" WHERE SongTitle = ?",
    "Parameters": [
        {
            "S": "Call Me Today"
        }
    ],
    "ConsistentRead": false,
    "NextToken": "change me"
}
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
