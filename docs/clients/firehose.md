---
layout: client
category: clients
name: Firehose
package: async-aws/firehose
---

## Usage


### Put record

```php
use AsyncAws\Firehose\FirehoseClient;
use AsyncAws\Firehose\Input\PutRecordInbound;
use AsyncAws\Firehose\ValueObject\Record;

$firehose = new FirehoseClient();

$output = $firehose->putRecord(new PutRecordInbound([
    'DeliveryStreamName' => 'example_stream',
    'Record' => new Record([
        'Data' => '{"message": "foo"}',
    ]),
]));

echo sprintf("Record id: %s\n", $output->getRecordId());
```

### Put record batch

```php
use AsyncAws\Firehose\FirehoseClient;
use AsyncAws\Firehose\Input\PutRecordBatchInbound;
use AsyncAws\Firehose\ValueObject\Record;

$firehose = new FirehoseClient();

$output = $firehose->putRecordBatch(new PutRecordBatchInbound([
    'DeliveryStreamName' => 'example_stream',
    'Records' => [
        new Record([
            'Data' => '{"message": "foo"}',
        ]),
        new Record([
            'Data' => '{"message": "bar"}',
        ]),
    ],
]));


echo sprintf("Failed count: %d\n", $response->getFailedPutCount());

foreach ($output->getRequestResponses() as $response) {
    echo sprintf("Record id: %s\n", $response->getRecordId());
}
```
