<?php

namespace AsyncAws\Kinesis\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Input\PutRecordInput;

class PutRecordInputTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new PutRecordInput([
            'StreamName' => 'change me',
            'Data' => 'change me',
            'PartitionKey' => 'change me',
            'ExplicitHashKey' => 'change me',
            'SequenceNumberForOrdering' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_PutRecord.html
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
