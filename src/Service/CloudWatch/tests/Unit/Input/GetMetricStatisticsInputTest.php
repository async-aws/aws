<?php

namespace AsyncAws\CloudWatch\Tests\Unit\Input;

use AsyncAws\CloudWatch\Enum\Statistic;
use AsyncAws\CloudWatch\Input\GetMetricStatisticsInput;
use AsyncAws\CloudWatch\ValueObject\Dimension;
use AsyncAws\Core\Test\TestCase;

class GetMetricStatisticsInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetMetricStatisticsInput([
            'Namespace' => 'foo',
            'MetricName' => 'bar',
            'Dimensions' => [new Dimension([
                'Name' => 'bar',
                'Value' => '123',
            ])],
            'StartTime' => new \DateTimeImmutable('2021-08-28T06:10:58+00:00'),
            'EndTime' => new \DateTimeImmutable('2021-08-29T06:10:58+00:00'),
            'Period' => 1337,
            'Statistics' => [Statistic::MINIMUM],
        ]);

        // see https://docs.aws.amazon.com/AmazonCloudWatch/latest/APIReference/API_GetMetricStatistics.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.0
            x-amz-target: GraniteServiceVersion20100801.GetMetricStatistics
            accept: application/json

            {
                "Dimensions": [
                    {
                        "Name": "bar",
                        "Value": "123"
                    }
                ],
                "EndTime": 1630217458,
                "MetricName": "bar",
                "Namespace": "foo",
                "Period": 1337,
                "StartTime": 1630131058,
                "Statistics": [
                    "Minimum"
                ]
            }';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
