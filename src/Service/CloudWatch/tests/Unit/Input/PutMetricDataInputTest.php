<?php

namespace AsyncAws\CloudWatch\Tests\Unit\Input;

use AsyncAws\CloudWatch\Input\PutMetricDataInput;
use AsyncAws\CloudWatch\ValueObject\Dimension;
use AsyncAws\CloudWatch\ValueObject\MetricDatum;
use AsyncAws\CloudWatch\ValueObject\StatisticSet;
use AsyncAws\Core\Test\TestCase;

class PutMetricDataInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new PutMetricDataInput([
            'Namespace' => 'foo',
            'MetricData' => [new MetricDatum([
                'MetricName' => 'bar',
                'Dimensions' => [new Dimension([
                    'Name' => 'bar',
                    'Value' => '123',
                ])],
                'Timestamp' => new \DateTimeImmutable('2021-08-28T06:05:55+00:00'),
                'Value' => 1337,
                'StatisticValues' => new StatisticSet([
                    'SampleCount' => 1337,
                    'Sum' => 1337,
                    'Minimum' => 1337,
                    'Maximum' => 1337,
                ]),
                'Values' => [1337],
                'Counts' => [1337],
                'StorageResolution' => 1337,
            ])],
        ]);

        // see https://docs.aws.amazon.com/AmazonCloudWatch/latest/APIReference/API_PutMetricData.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=PutMetricData&
            Version=2010-08-01&
            Namespace=foo&
            MetricData.member.1.MetricName=bar&
            MetricData.member.1.Dimensions.member.1.Name=bar&
            MetricData.member.1.Dimensions.member.1.Value=123&
            MetricData.member.1.Timestamp=2021-08-28T06%3A05%3A55%2B00%3A00&
            MetricData.member.1.Value=1337&
            MetricData.member.1.StatisticValues.SampleCount=1337&
            MetricData.member.1.StatisticValues.Sum=1337&
            MetricData.member.1.StatisticValues.Minimum=1337&
            MetricData.member.1.StatisticValues.Maximum=1337&
            MetricData.member.1.Values.member.1=1337&
            MetricData.member.1.Counts.member.1=1337&
            MetricData.member.1.StorageResolution=1337
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
