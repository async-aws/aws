<?php

namespace AsyncAws\Kinesis\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Input\GetRecordsInput;

class GetRecordsInputTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new GetRecordsInput([
            'ShardIterator' => 'change me',
            'Limit' => 1337,
        ]);

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_GetRecords.html
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
