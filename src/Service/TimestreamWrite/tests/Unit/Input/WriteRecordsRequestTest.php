<?php

namespace AsyncAws\TimestreamWrite\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\TimestreamWrite\Enum\MeasureValueType;
use AsyncAws\TimestreamWrite\Input\WriteRecordsRequest;
use AsyncAws\TimestreamWrite\ValueObject\Dimension;
use AsyncAws\TimestreamWrite\ValueObject\Record;

class WriteRecordsRequestTest extends TestCase
{
    public function testRequest(): void
    {
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

        // see https://docs.aws.amazon.com/timestream/latest/developerguide/API_Operations_Amazon_Timestream_Write.html/API_WriteRecords.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.0
            x-amz-target: Timestream_20181101.WriteRecords
            Accept: application/json

            {
                "CommonAttributes": {
                    "Dimensions": [
                        {
                            "Name": "region",
                            "Value": "us-east-1"
                        }
                    ]
                },
                "DatabaseName": "db",
                "Records": [
                    {
                        "MeasureName": "cpu_utilization",
                        "MeasureValue": "13.5",
                        "MeasureValueType": "DOUBLE",
                        "Time": 12345678
                    },
                    {
                        "MeasureName": "memory_utilization",
                        "MeasureValue": "40",
                        "MeasureValueType": "BIGINT",
                        "Time": 12345678
                    }
                ],
                "TableName": "tbl"
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
