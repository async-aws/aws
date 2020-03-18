<?php

namespace AsyncAws\DynamoDb\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\Input\UpdateTableInput;
use AsyncAws\DynamoDb\ValueObject\ProvisionedThroughput;

class UpdateTableInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new UpdateTableInput([
            'TableName' => 'Thread',
            'ProvisionedThroughput' => new ProvisionedThroughput([
                'ReadCapacityUnits' => 1337,
                'WriteCapacityUnits' => 1337,
            ]),
        ]);

        // see example-1.json from SDK
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.0
X-AMZ-Target: DynamoDB_20120810.UpdateTable

{
    "TableName": "Thread",
    "ProvisionedThroughput": {
        "ReadCapacityUnits": 1337,
        "WriteCapacityUnits": 1337
    }
}';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
