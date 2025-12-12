<?php

namespace AsyncAws\CloudWatch\Tests\Unit\Input;

use AsyncAws\CloudWatch\Input\GetMetricDataInput;
use AsyncAws\CloudWatch\ValueObject\Dimension;
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
                'Id' => 'q1',
                'Expression' => 'SELECT AVG(CPUUtilization) FROM SCHEMA("AWS/EC2", InstanceId)',
                'Label' => 'Cluster CpuUtilization',
                'Period' => 300,
            ]), new MetricDataQuery([
                'Id' => 'm1',
                'Label' => 'Unhealthy Behind Load Balancer',
                'MetricStat' => new MetricStat([
                    'Metric' => new Metric([
                        'Namespace' => 'AWS/ApplicationELB',
                        'MetricName' => 'UnHealthyHostCount',
                        'Dimensions' => [new Dimension([
                            'Name' => 'TargetGroup',
                            'Value' => 'targetgroup/EC2Co-Defau-EXAMPLEWNAD/89cc68152b367e5f',
                        ]), new Dimension([
                            'Name' => 'LoadBalancer',
                            'Value' => 'app/EC2Co-EcsEl-EXAMPLE69Q/fdd2210e799e4376',
                        ])],
                    ]),
                    'Period' => 300,
                    'Stat' => 'Average',
                ]),
            ])],
            'StartTime' => new \DateTimeImmutable('2021-11-16T11:25:00+00:00'),
            'EndTime' => new \DateTimeImmutable('2021-11-16T14:55:00+00:00'),
        ]);

        // see https://docs.aws.amazon.com/AmazonCloudWatch/latest/APIReference/API_GetMetricData.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.0
            x-amz-target: GraniteServiceVersion20100801.GetMetricData
            accept: application/json

            {
                "StartTime": 1637061900,
                "EndTime": 1637074500,
                "MetricDataQueries": [
                    {
                        "Expression": "SELECT AVG(CPUUtilization) FROM SCHEMA(\"AWS/EC2\", InstanceId)",
                        "Id": "q1",
                        "Period": 300,
                        "Label": "Cluster CpuUtilization"
                    },
                    {
                        "Id": "m1",
                        "Label": "Unhealthy Behind Load Balancer",
                        "MetricStat": {
                            "Metric": {
                                "Namespace": "AWS/ApplicationELB",
                                "MetricName": "UnHealthyHostCount",
                                "Dimensions": [
                                    {
                                        "Name": "TargetGroup",
                                        "Value": "targetgroup/EC2Co-Defau-EXAMPLEWNAD/89cc68152b367e5f"
                                    },
                                    {
                                        "Name": "LoadBalancer",
                                        "Value": "app/EC2Co-EcsEl-EXAMPLE69Q/fdd2210e799e4376"
                                    }
                                ]
                            },
                            "Period": 300,
                            "Stat": "Average"
                        }
                    }
                ]
            }';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
