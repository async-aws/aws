<?php

namespace AsyncAws\TimestreamQuery\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\TimestreamQuery\Input\QueryRequest;

class QueryRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new QueryRequest([
            'ClientToken' => 'qwertyuiop',
            'QueryString' => 'SELECT * FROM db.tbl ORDER BY time DESC LIMIT 10',
        ]);

        // see https://docs.aws.amazon.com/timestream/latest/developerguide/API_Operations_Amazon_Timestream_Query.html/API_Query.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.0
            x-amz-target: Timestream_20181101.Query
            Accept: application/json

            {
                "ClientToken": "qwertyuiop",
                "QueryString": "SELECT * FROM db.tbl ORDER BY time DESC LIMIT 10"
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
