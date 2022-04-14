---
layout: client
category: clients
name: Timestream Write
package: async-aws/timestream-write
---

## Usage

### Write records

```php
use AsyncAws\TimestreamWrite\Enum\MeasureValueType;
use AsyncAws\TimestreamWrite\Input\WriteRecordsResponse;
use AsyncAws\TimestreamWrite\TimestreamWriteClient;
use AsyncAws\TimestreamWrite\ValueObject\Dimension;
use AsyncAws\TimestreamWrite\ValueObject\Record;

$timestreamWrite = new TimestreamWriteClient();

$timestreamWrite->writeRecords(new WriteRecordsRequest([
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
]));
```
