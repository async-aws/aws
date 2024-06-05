<?php

namespace AsyncAws\Kinesis\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Input\PutRecordInput;

class PutRecordInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new PutRecordInput([
            'StreamName' => 'exampleStreamName',
            'Data' => '_<data>_1',
            'PartitionKey' => 'partitionKey',
        ]);

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_PutRecord.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
x-amz-target: Kinesis_20131202.PutRecord
Accept: application/json

{
  "StreamName": "exampleStreamName",
  "Data": "XzxkYXRhPl8x",
  "PartitionKey": "partitionKey"
} ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
