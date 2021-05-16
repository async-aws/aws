<?php

namespace AsyncAws\Kinesis\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Input\PutRecordsInput;
use AsyncAws\Kinesis\ValueObject\PutRecordsRequestEntry;

class PutRecordsInputTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new PutRecordsInput([
            'Records' => [new PutRecordsRequestEntry([
                'Data' => 'change me',
                'ExplicitHashKey' => 'change me',
                'PartitionKey' => 'change me',
            ])],
            'StreamName' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_PutRecords.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1

            {
            "change": "it"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
