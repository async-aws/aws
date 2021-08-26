<?php

namespace AsyncAws\CloudWatch\Tests\Integration;

use AsyncAws\CloudWatch\CloudWatchClient;
use AsyncAws\CloudWatch\Input\GetMetricDataInput;
use AsyncAws\CloudWatch\Input\GetMetricStatisticsInput;
use AsyncAws\CloudWatch\Input\ListMetricsInput;
use AsyncAws\CloudWatch\Input\PutMetricDataInput;
use AsyncAws\CloudWatch\ValueObject\Dimension;
use AsyncAws\CloudWatch\ValueObject\DimensionFilter;
use AsyncAws\CloudWatch\ValueObject\LabelOptions;
use AsyncAws\CloudWatch\ValueObject\Metric;
use AsyncAws\CloudWatch\ValueObject\MetricDataQuery;
use AsyncAws\CloudWatch\ValueObject\MetricDatum;
use AsyncAws\CloudWatch\ValueObject\MetricStat;
use AsyncAws\CloudWatch\ValueObject\StatisticSet;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;

class CloudWatchClientTest extends TestCase
{
    public function testGetMetricData(): void
    {
        self::markTestIncomplete();

        $client = $this->getClient();

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
        $result = $client->getMetricData($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getMetricDataResults());
        self::assertSame('changeIt', $result->getNextToken());
        // self::assertTODO(expected, $result->getMessages());
    }

    public function testGetMetricStatistics(): void
    {
        self::markTestIncomplete();

        $client = $this->getClient();

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
        $result = $client->getMetricStatistics($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getLabel());
        // self::assertTODO(expected, $result->getDatapoints());
    }

    public function testListMetrics(): void
    {
        self::markTestIncomplete();

        $client = $this->getClient();

        $input = new ListMetricsInput([
            'Namespace' => 'foo',
            'MetricName' => 'bar',
            'Dimensions' => [new DimensionFilter([
                'Name' => 'bar',
                'Value' => '123',
            ])],
            'NextToken' => 'change me',
            'RecentlyActive' => 'change me',
        ]);
        $result = $client->listMetrics($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getMetrics());
        self::assertSame('changeIt', $result->getNextToken());
    }

    public function testPutMetricData(): void
    {
        self::markTestIncomplete();

        $client = $this->getClient();

        $input = new PutMetricDataInput([
            'Namespace' => 'foo',
            'MetricData' => [new MetricDatum([
                'MetricName' => 'bar',
                'Dimensions' => [new Dimension([
                    'Name' => 'bar',
                    'Value' => '123',
                ])],
                'Timestamp' => new \DateTimeImmutable(),
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
        $result = $client->putMetricData($input);

        $result->resolve();
    }

    private function getClient(): CloudWatchClient
    {
        self::fail('Not implemented');

        return new CloudWatchClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
