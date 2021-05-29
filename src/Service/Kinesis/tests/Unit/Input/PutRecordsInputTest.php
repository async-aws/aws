<?php

namespace AsyncAws\Kinesis\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Input\PutRecordsInput;
use AsyncAws\Kinesis\ValueObject\PutRecordsRequestEntry;

class PutRecordsInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new PutRecordsInput([
            'Records' => [new PutRecordsRequestEntry([
                'Data' => '_<data>_1',
                'PartitionKey' => 'partitionKey1',
            ]), new PutRecordsRequestEntry([
                'Data' => '_<data>_2',
                'PartitionKey' => 'partitionKey2',
            ]), new PutRecordsRequestEntry([
                'Data' => '_<data>_3',
                'PartitionKey' => 'partitionKey3',
            ])],
            'StreamName' => 'exampleStreamName',
        ]);

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_PutRecords.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
X-Amz-Target: Kinesis_20131202.PutRecords

{
    "Records": [
        {
            "Data": "XzxkYXRhPl8x",
            "PartitionKey": "partitionKey1"
        },
        {
            "Data": "XzxkYXRhPl8y",
            "PartitionKey": "partitionKey2"
        },
        {
            "Data": "XzxkYXRhPl8z",
            "PartitionKey": "partitionKey3"
        }
    ],
    "StreamName": "exampleStreamName"
}';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
