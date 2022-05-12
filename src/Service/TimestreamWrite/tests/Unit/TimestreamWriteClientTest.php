<?php

namespace AsyncAws\TimestreamWrite\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\TimestreamWrite\Enum\MeasureValueType;
use AsyncAws\TimestreamWrite\Input\DescribeEndpointsRequest;
use AsyncAws\TimestreamWrite\Input\WriteRecordsRequest;
use AsyncAws\TimestreamWrite\Result\DescribeEndpointsResponse;
use AsyncAws\TimestreamWrite\Result\WriteRecordsResponse;
use AsyncAws\TimestreamWrite\TimestreamWriteClient;
use AsyncAws\TimestreamWrite\ValueObject\Dimension;
use AsyncAws\TimestreamWrite\ValueObject\Record;
use Symfony\Component\HttpClient\MockHttpClient;

class TimestreamWriteClientTest extends TestCase
{
    public function testDescribeEndpoints(): void
    {
        $client = new TimestreamWriteClient([], new NullProvider(), new MockHttpClient());

        $input = new DescribeEndpointsRequest([

        ]);
        $result = $client->describeEndpoints($input);

        self::assertInstanceOf(DescribeEndpointsResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testWriteRecords(): void
    {
        $client = new TimestreamWriteClient([], new NullProvider(), new MockHttpClient());

        $input = new WriteRecordsRequest([
            'EndpointAddress' => 'ingest-cell2.timestream.us-east-1.amazonaws.com',
            'DatabaseName' => 'db',
            'TableName' => 'tbl',
            'CommonAttributes' => new Record([
                'Dimensions' => [
                    new Dimension(['Name' => 'region', 'Value' => 'us-east-1']),
                ],
            ]),
            'Records' => [
                new Record([
                    'MeasureName' => 'cpu_utilization',
                    'MeasureValue' => '13.5',
                    'MeasureValueType' => MeasureValueType::DOUBLE,
                    'Time' => 12345678,
                ]),
                new Record([
                    'MeasureName' => 'memory_utilization',
                    'MeasureValue' => '40',
                    'MeasureValueType' => MeasureValueType::BIGINT,
                    'Time' => 12345678,
                ]),
            ],
        ]);
        $result = $client->writeRecords($input);

        self::assertInstanceOf(WriteRecordsResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
