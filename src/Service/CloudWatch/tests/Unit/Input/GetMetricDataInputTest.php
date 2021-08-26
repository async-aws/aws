<?php

namespace AsyncAws\CloudWatch\Tests\Unit\Input;

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
            'StartTime' => new \DateTimeImmutable(),
            'EndTime' => new \DateTimeImmutable(),
            'NextToken' => 'change me',
            'ScanBy' => 'change me',
            'MaxDatapoints' => 1337,
            'LabelOptions' => new LabelOptions([
                'Timezone' => 'change me',
            ]),
        ]);

        // see https://docs.aws.amazon.com/AmazonCloudWatch/latest/APIReference/API_GetMetricData.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=GetMetricData
            &Version=2010-08-01
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
