<?php

namespace AsyncAws\CloudWatch\Tests\Unit\Input;

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
            'StartTime' => new \DateTimeImmutable(),
            'EndTime' => new \DateTimeImmutable(),
            'Period' => 1337,
            'Statistics' => ['change me'],
            'ExtendedStatistics' => ['change me'],
        ]);

        // see https://docs.aws.amazon.com/AmazonCloudWatch/latest/APIReference/API_GetMetricStatistics.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=GetMetricStatistics
            &Version=2010-08-01
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
