<?php

namespace AsyncAws\Firehose\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Firehose\Input\PutRecordInput;
use AsyncAws\Firehose\ValueObject\Record;

class PutRecordInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new PutRecordInput([
            'DeliveryStreamName' => 'streamfoo',
            'Record' => new Record([
                'Data' => '{"message": "bar"}',
            ]),
        ]);

        // see https://docs.aws.amazon.com/firehose/latest/APIReference/API_PutRecord.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: Firehose_20150804.PutRecord

            {
                "DeliveryStreamName": "streamfoo",
                "Record": {
                    "Data": "eyJtZXNzYWdlIjogImJhciJ9"
                }
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
