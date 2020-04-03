<?php

namespace AsyncAws\CloudWatch\Tests\Integration;

use AsyncAws\CloudWatch\CloudWatchClient;
use AsyncAws\CloudWatch\Input\PutMetricAlarmInput;
use AsyncAws\CloudWatch\ValueObject\Dimension;
use AsyncAws\CloudWatch\ValueObject\Metric;
use AsyncAws\CloudWatch\ValueObject\MetricDataQuery;
use AsyncAws\CloudWatch\ValueObject\MetricStat;
use AsyncAws\CloudWatch\ValueObject\Tag;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;

class CloudWatchClientTest extends TestCase
{
    public function testPutMetricAlarm(): void
    {
        $client = $this->getClient();

        $input = new PutMetricAlarmInput([
            'AlarmName' => 'changeMe',
            'AlarmDescription' => 'changeMe',
            'ActionsEnabled' => false,
            'OKActions' => ['changeMe'],
            'AlarmActions' => ['changeMe'],
            'InsufficientDataActions' => ['changeMe'],
            'MetricName' => 'changeMe',
            'Namespace' => 'changeMe',
            'Statistic' => 'changeMe',
            'ExtendedStatistic' => 'changeMe',
            'Dimensions' => [new Dimension([
                'Name' => 'changeMe',
                'Value' => 'changeMe',
            ])],
            'Period' => 1337,
            'Unit' => 'changeMe',
            'EvaluationPeriods' => 1337,
            'DatapointsToAlarm' => 1337,
            'Threshold' => 1337,
            'ComparisonOperator' => 'changeMe',
            'TreatMissingData' => 'changeMe',
            'EvaluateLowSampleCountPercentile' => 'changeMe',
            'Metrics' => [new MetricDataQuery([
                'Id' => 'changeMe',
                'MetricStat' => new MetricStat([
                    'Metric' => new Metric([
                        'Namespace' => 'changeMe',
                        'MetricName' => 'changeMe',
                        'Dimensions' => [new Dimension([
                            'Name' => 'changeMe',
                            'Value' => 'changeMe',
                        ])],
                    ]),
                    'Period' => 1337,
                    'Stat' => 'changeMe',
                    'Unit' => 'changeMe',
                ]),
                'Expression' => 'changeMe',
                'Label' => 'changeMe',
                'ReturnData' => false,
                'Period' => 1337,
            ])],
            'Tags' => [new Tag([
                'Key' => 'changeMe',
                'Value' => 'changeMe',
            ])],
            'ThresholdMetricId' => 'changeMe',
        ]);
        $result = $client->PutMetricAlarm($input);

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
