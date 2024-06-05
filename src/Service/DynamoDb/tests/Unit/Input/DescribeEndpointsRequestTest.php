<?php

namespace AsyncAws\DynamoDb\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\Input\DescribeEndpointsRequest;

class DescribeEndpointsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DescribeEndpointsRequest([
        ]);

        // see https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_DescribeEndpoints.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.0
            x-amz-target: DynamoDB_20120810.DescribeEndpoints
            Accept: application/json

            {
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
