<?php

namespace AsyncAws\CloudWatch\Tests\Unit;

use AsyncAws\CloudWatch\CloudWatchClient;
use AsyncAws\CloudWatch\Input\GetMetricDataInput;
use AsyncAws\CloudWatch\Input\GetMetricStatisticsInput;
use AsyncAws\CloudWatch\Input\ListMetricsInput;
use AsyncAws\CloudWatch\Input\PutMetricDataInput;
use AsyncAws\CloudWatch\Result\GetMetricDataOutput;
use AsyncAws\CloudWatch\Result\GetMetricStatisticsOutput;
use AsyncAws\CloudWatch\Result\ListMetricsOutput;
use AsyncAws\CloudWatch\ValueObject\MetricDataQuery;
use AsyncAws\CloudWatch\ValueObject\MetricDatum;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Result;
use AsyncAws\Core\Test\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class CloudWatchClientTest extends TestCase
{
    public function testGetMetricData(): void
    {
        $client = new CloudWatchClient([], new NullProvider(), new MockHttpClient());

        $input = new GetMetricDataInput([
            'MetricDataQueries' => [new MetricDataQuery([
                'Id' => 'id',

            ])],
            'StartTime' => new \DateTimeImmutable(),
            'EndTime' => new \DateTimeImmutable(),

        ]);
        $result = $client->getMetricData($input);

        self::assertInstanceOf(GetMetricDataOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetMetricStatistics(): void
    {
        $client = new CloudWatchClient([], new NullProvider(), new MockHttpClient());

        $input = new GetMetricStatisticsInput([
            'Namespace' => 'foo',
            'MetricName' => 'bar',
            'StartTime' => new \DateTimeImmutable(),
            'EndTime' => new \DateTimeImmutable(),
            'Period' => 1337,

        ]);
        $result = $client->getMetricStatistics($input);

        self::assertInstanceOf(GetMetricStatisticsOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListMetrics(): void
    {
        $client = new CloudWatchClient([], new NullProvider(), new MockHttpClient());

        $input = new ListMetricsInput([
            'Namespace' => 'foo',
            'MetricName' => 'bar',
        ]);
        $result = $client->listMetrics($input);

        self::assertInstanceOf(ListMetricsOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testPutMetricData(): void
    {
        $client = new CloudWatchClient([], new NullProvider(), new MockHttpClient());

        $input = new PutMetricDataInput([
            'Namespace' => 'foo',
            'MetricData' => [new MetricDatum([
                'MetricName' => 'bar',

            ])],
        ]);
        $result = $client->putMetricData($input);

        self::assertInstanceOf(Result::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
