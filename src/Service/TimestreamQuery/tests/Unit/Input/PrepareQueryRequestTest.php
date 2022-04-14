<?php

namespace AsyncAws\TimestreamQuery\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\TimestreamQuery\Input\PrepareQueryRequest;

class PrepareQueryRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new PrepareQueryRequest([
            'QueryString' => 'SELECT * FROM db.tbl ORDER BY time DESC LIMIT 10',
            'ValidateOnly' => true,
        ]);

        // see https://docs.aws.amazon.com/timestream/latest/developerguide/API_Operations_Amazon_Timestream_Query.html/API_PrepareQuery.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.0
            x-amz-target: Timestream_20181101.PrepareQuery

            {
                "QueryString": "SELECT * FROM db.tbl ORDER BY time DESC LIMIT 10",
                "ValidateOnly": true
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
