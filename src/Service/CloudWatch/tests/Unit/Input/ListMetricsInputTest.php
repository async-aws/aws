<?php

namespace AsyncAws\CloudWatch\Tests\Unit\Input;

use AsyncAws\CloudWatch\Enum\RecentlyActive;
use AsyncAws\CloudWatch\Input\ListMetricsInput;
use AsyncAws\CloudWatch\ValueObject\DimensionFilter;
use AsyncAws\Core\Test\TestCase;

class ListMetricsInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListMetricsInput([
            'Namespace' => 'foo',
            'MetricName' => 'bar',
            'Dimensions' => [new DimensionFilter([
                'Name' => 'bar',
                'Value' => '123',
            ])],
            'NextToken' => 'change me',
            'RecentlyActive' => RecentlyActive::PT3H,
        ]);

        // see https://docs.aws.amazon.com/AmazonCloudWatch/latest/APIReference/API_ListMetrics.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=ListMetrics&
            Version=2010-08-01&
            Namespace=foo&
            MetricName=bar&
            Dimensions.member.1.Name=bar&
            Dimensions.member.1.Value=123&
            NextToken=change+me&
            RecentlyActive=PT3H
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
