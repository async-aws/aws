<?php

namespace AsyncAws\Kinesis\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Input\GetShardIteratorInput;

class GetShardIteratorInputTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new GetShardIteratorInput([
            'StreamName' => 'change me',
            'ShardId' => 'change me',
            'ShardIteratorType' => 'change me',
            'StartingSequenceNumber' => 'change me',
            'Timestamp' => new \DateTimeImmutable(),
        ]);

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_GetShardIterator.html
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
