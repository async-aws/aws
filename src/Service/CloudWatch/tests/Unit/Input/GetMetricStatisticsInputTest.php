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
            Content-Type: application/x-www-form-urlencoded

            Action=GetMetricStatistics&
            Version=2010-08-01&
            Namespace=foo&
            MetricName=bar&
            Dimensions.member.1.Name=bar&
            Dimensions.member.1.Value=123&
            StartTime=2021-08-28T06%3A10%3A58%2B00%3A00&
            EndTime=2021-08-29T06%3A10%3A58%2B00%3A00&
            Period=1337&
            Statistics.member.1=Minimum
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
