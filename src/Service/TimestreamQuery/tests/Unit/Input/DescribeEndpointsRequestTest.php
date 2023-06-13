<?php

namespace AsyncAws\TimestreamQuery\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\TimestreamQuery\Input\DescribeEndpointsRequest;

class DescribeEndpointsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DescribeEndpointsRequest([
        ]);

        // see https://docs.aws.amazon.com/timestream/latest/developerguide/API_Operations_Amazon_Timestream_Query.html/API_DescribeEndpoints.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.0
            x-amz-target: Timestream_20181101.DescribeEndpoints

            {
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
