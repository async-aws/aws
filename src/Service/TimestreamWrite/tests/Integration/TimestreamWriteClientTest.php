<?php

namespace AsyncAws\TimestreamWrite\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\TimestreamWrite\Input\WriteRecordsRequest;
use AsyncAws\TimestreamWrite\TimestreamWriteClient;
use AsyncAws\TimestreamWrite\ValueObject\Dimension;
use AsyncAws\TimestreamWrite\ValueObject\MeasureValue;
use AsyncAws\TimestreamWrite\ValueObject\Record;

class TimestreamWriteClientTest extends TestCase
{
    public function testWriteRecords(): void
    {
        self::markTestIncomplete('Cannot test without support for timestream.');

        $client = $this->getClient();

        $input = new WriteRecordsRequest([
            'DatabaseName' => 'change me',
            'TableName' => 'change me',
            'CommonAttributes' => new Record([
                'Dimensions' => [new Dimension([
                    'Name' => 'change me',
                    'Value' => 'change me',
                    'DimensionValueType' => 'change me',
                ])],
                'MeasureName' => 'change me',
                'MeasureValue' => 'change me',
                'MeasureValueType' => 'change me',
                'Time' => 'change me',
                'TimeUnit' => 'change me',
                'Version' => 1337,
                'MeasureValues' => [new MeasureValue([
                    'Name' => 'change me',
                    'Value' => 'change me',
                    'Type' => 'change me',
                ])],
            ]),
            'Records' => [new Record([
                'Dimensions' => [new Dimension([
                    'Name' => 'change me',
                    'Value' => 'change me',
                    'DimensionValueType' => 'change me',
                ])],
                'MeasureName' => 'change me',
                'MeasureValue' => 'change me',
                'MeasureValueType' => 'change me',
                'Time' => 'change me',
                'TimeUnit' => 'change me',
                'Version' => 1337,
                'MeasureValues' => [new MeasureValue([
                    'Name' => 'change me',
                    'Value' => 'change me',
                    'Type' => 'change me',
                ])],
            ])],
        ]);
        $result = $client->writeRecords($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getRecordsIngested());
    }

    private function getClient(): TimestreamWriteClient
    {
        self::fail('Not implemented');

        return new TimestreamWriteClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
