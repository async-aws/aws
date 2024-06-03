<?php

namespace AsyncAws\DynamoDb\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\Input\ListTablesInput;

class ListTablesInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListTablesInput([
            'ExclusiveStartTableName' => 'Forum',
            'Limit' => 3,
        ]);

        // see https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_POST.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.0
x-amz-target: DynamoDB_20120810.ListTables
Accept: application/json

{
    "ExclusiveStartTableName": "Forum",
    "Limit": 3
}
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
