<?php

namespace AsyncAws\TimestreamQuery\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\TimestreamQuery\Input\CancelQueryRequest;

class CancelQueryRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CancelQueryRequest([
            'QueryId' => 'qwertyuiop',
        ]);

        // see https://docs.aws.amazon.com/timestream/latest/developerguide/API_Operations_Amazon_Timestream_Query.html/API_CancelQuery.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.0
            x-amz-target: Timestream_20181101.CancelQuery
            Accept: application/json

            {
                "QueryId": "qwertyuiop"
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
