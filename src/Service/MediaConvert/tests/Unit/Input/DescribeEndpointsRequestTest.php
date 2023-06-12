<?php

namespace AsyncAws\MediaConvert\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\MediaConvert\Enum\DescribeEndpointsMode;
use AsyncAws\MediaConvert\Input\DescribeEndpointsRequest;

class DescribeEndpointsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DescribeEndpointsRequest([
            'MaxResults' => 1337,
            'Mode' => DescribeEndpointsMode::DEFAULT,
            'NextToken' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/mediaconvert/latest/apireference/API_DescribeEndpoints.html
        $expected = '
            POST /2017-08-29/endpoints HTTP/1.0
            Content-Type: application/json

            {
                "maxResults": 1337,
                "mode": "DEFAULT",
                "nextToken": "change me"
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
