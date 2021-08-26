<?php

namespace AsyncAws\Firehose\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Firehose\FirehoseClient;
use AsyncAws\Firehose\Input\PutRecordBatchInput;
use AsyncAws\Firehose\Input\PutRecordInput;
use AsyncAws\Firehose\Result\PutRecordBatchOutput;
use AsyncAws\Firehose\Result\PutRecordOutput;
use AsyncAws\Firehose\ValueObject\Record;
use Symfony\Component\HttpClient\MockHttpClient;

class FirehoseClientTest extends TestCase
{
    public function testPutRecord(): void
    {
        $client = new FirehoseClient([], new NullProvider(), new MockHttpClient());

        $input = new PutRecordInput([
            'DeliveryStreamName' => 'change me',
            'Record' => new Record([
                'Data' => '{"message": "bar"}',
            ]),
        ]);
        $result = $client->putRecord($input);

        self::assertInstanceOf(PutRecordOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testPutRecordBatch(): void
    {
        $client = new FirehoseClient([], new NullProvider(), new MockHttpClient());

        $input = new PutRecordBatchInput([
            'DeliveryStreamName' => 'change me',
            'Records' => [
                new Record([
                    'Data' => '{"message": "bar"}',
                ]),
                new Record([
                    'Data' => '{"message": "baz"}',
                ]),
            ],
        ]);
        $result = $client->putRecordBatch($input);

        self::assertInstanceOf(PutRecordBatchOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
