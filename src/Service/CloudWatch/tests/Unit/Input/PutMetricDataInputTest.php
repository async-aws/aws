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
            Content-Type: application/x-amz-json-1.0
            x-amz-target: GraniteServiceVersion20100801.PutMetricData
            accept: application/json

            {
                "MetricData": [
                    {
                        "Counts": [
                            1337
                        ],
                        "Dimensions": [
                            {
                                "Name": "bar",
                                "Value": "123"
                            }
                        ],
                        "MetricName": "bar",
                        "StatisticValues": {
                            "Maximum": 1337,
                            "Minimum": 1337,
                            "SampleCount": 1337,
                            "Sum": 1337
                        },
                        "StorageResolution": 1337,
                        "Timestamp": 1630130755,
                        "Value": 1337,
                        "Values": [
                            1337
                        ]
                    }
                ],
                "Namespace": "foo"
            }';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
