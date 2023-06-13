<?php

namespace AsyncAws\Kinesis\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Input\DescribeLimitsInput;

class DescribeLimitsInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DescribeLimitsInput([
        ]);

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_DescribeLimits.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
X-Amz-Target: Kinesis_20131202.DescribeLimits

{}';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
