<?php

namespace AsyncAws\TimestreamWrite\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\TimestreamWrite\Input\DescribeEndpointsRequest;

class DescribeEndpointsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DescribeEndpointsRequest([
        ]);

        // see https://docs.aws.amazon.com/timestream/latest/developerguide/API_Operations_Amazon_Timestream_Write.html/API_DescribeEndpoints.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.0
            x-amz-target: Timestream_20181101.DescribeEndpoints
            Accept: application/json

            {
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
