<?php

namespace AsyncAws\DynamoDb\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\Input\UpdateTimeToLiveInput;
use AsyncAws\DynamoDb\ValueObject\TimeToLiveSpecification;

class UpdateTimeToLiveInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new UpdateTimeToLiveInput([
            'TableName' => 'table name',
            'TimeToLiveSpecification' => new TimeToLiveSpecification([
                'Enabled' => false,
                'AttributeName' => 'attribute',
            ]),
        ]);

        // see https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_UpdateTimeToLive.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.0
            X-Amz-Target: DynamoDB_20120810.UpdateTimeToLive

            {
               "TableName": "table name",
               "TimeToLiveSpecification": {
                  "AttributeName": "attribute",
                  "Enabled": false
               }
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
