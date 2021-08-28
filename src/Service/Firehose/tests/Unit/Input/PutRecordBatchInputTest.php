<?php

namespace AsyncAws\Firehose\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Firehose\Input\PutRecordBatchInput;
use AsyncAws\Firehose\ValueObject\Record;

class PutRecordBatchInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new PutRecordBatchInput([
            'DeliveryStreamName' => 'streamfoo',
            'Records' => [
                new Record([
                    'Data' => '{"message": "bar"}',
                ]),
                new Record([
                    'Data' => '{"message": "baz"}',
                ]),
            ],
        ]);

        // see https://docs.aws.amazon.com/firehose/latest/APIReference/API_PutRecordBatch.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: Firehose_20150804.PutRecordBatch

            {
                "DeliveryStreamName": "streamfoo",
                "Records": [
                    {
                        "Data": "eyJtZXNzYWdlIjogImJhciJ9"
                    },
                    {
                        "Data": "eyJtZXNzYWdlIjogImJheiJ9"
                    }
                ]
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
