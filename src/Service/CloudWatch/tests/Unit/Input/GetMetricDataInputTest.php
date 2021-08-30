<?php

namespace AsyncAws\CloudWatch\Tests\Unit\Input;

use AsyncAws\CloudWatch\Enum\ScanBy;
use AsyncAws\CloudWatch\Input\GetMetricDataInput;
use AsyncAws\CloudWatch\ValueObject\Dimension;
use AsyncAws\CloudWatch\ValueObject\LabelOptions;
use AsyncAws\CloudWatch\ValueObject\Metric;
use AsyncAws\CloudWatch\ValueObject\MetricDataQuery;
use AsyncAws\CloudWatch\ValueObject\MetricStat;
use AsyncAws\Core\Test\TestCase;

class GetMetricDataInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetMetricDataInput([
            'MetricDataQueries' => [new MetricDataQuery([
                'Id' => 'change me',
                'MetricStat' => new MetricStat([
                    'Metric' => new Metric([
                        'Namespace' => 'foo',
                        'MetricName' => 'bar',
                        'Dimensions' => [new Dimension([
                            'Name' => 'bar',
                            'Value' => '123',
                        ])],
                    ]),
                    'Period' => 1337,
                    'Stat' => 'Average',
                ]),
                'Expression' => 'change me',
                'Label' => 'hello world',
                'ReturnData' => false,
                'Period' => 1337,
            ])],
            'StartTime' => new \DateTimeImmutable('2021-08-28T06:10:58+00:00'),
            'EndTime' => new \DateTimeImmutable('2021-08-29T06:10:58+00:00'),
            'NextToken' => 'change me',
            'ScanBy' => ScanBy::TIMESTAMP_ASCENDING,
            'MaxDatapoints' => 1337,
            'LabelOptions' => new LabelOptions([
                'Timezone' => 'change me',
            ]),
        ]);

        // see https://docs.aws.amazon.com/AmazonCloudWatch/latest/APIReference/API_GetMetricData.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=GetMetricData&
            Version=2010-08-01&
            MetricDataQueries.member.1.Id=change+me&
            MetricDataQueries.member.1.MetricStat.Metric.Namespace=foo&
            MetricDataQueries.member.1.MetricStat.Metric.MetricName=bar&
            MetricDataQueries.member.1.MetricStat.Metric.Dimensions.member.1.Name=bar&
            MetricDataQueries.member.1.MetricStat.Metric.Dimensions.member.1.Value=123&
            MetricDataQueries.member.1.MetricStat.Period=1337&
            MetricDataQueries.member.1.MetricStat.Stat=Average&
            MetricDataQueries.member.1.Expression=change+me&
            MetricDataQueries.member.1.Label=hello+world&
            MetricDataQueries.member.1.ReturnData=false&
            MetricDataQueries.member.1.Period=1337&
            StartTime=2021-08-28T06%3A10%3A58%2B00%3A00&
            EndTime=2021-08-29T06%3A10%3A58%2B00%3A00&
            NextToken=change+me&
            ScanBy=TimestampAscending&
            MaxDatapoints=1337&
            LabelOptions.Timezone=change+me
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
