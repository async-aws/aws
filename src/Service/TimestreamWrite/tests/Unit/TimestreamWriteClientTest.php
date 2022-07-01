<?php

namespace AsyncAws\TimestreamWrite\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\TimestreamWrite\Enum\MeasureValueType;
use AsyncAws\TimestreamWrite\Input\WriteRecordsRequest;
use AsyncAws\TimestreamWrite\Result\WriteRecordsResponse;
use AsyncAws\TimestreamWrite\TimestreamWriteClient;
use AsyncAws\TimestreamWrite\ValueObject\Dimension;
use AsyncAws\TimestreamWrite\ValueObject\Record;
use Symfony\Component\HttpClient\MockHttpClient;

class TimestreamWriteClientTest extends TestCase
{
    public function testWriteRecords(): void
    {
        $client = new TimestreamWriteClient([], new NullProvider(), new MockHttpClient([new SimpleMockedResponse('{
          "Endpoints": [{
            "Address": "www.aws.com",
            "CachePeriodInMinutes": 1234
          }]
        }'), new SimpleMockedResponse('{}')]));

        $input = new WriteRecordsRequest([
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
