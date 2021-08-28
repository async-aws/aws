<?php

namespace AsyncAws\Firehose\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Firehose\FirehoseClient;
use AsyncAws\Firehose\Input\PutRecordBatchInput;
use AsyncAws\Firehose\Input\PutRecordInput;
use AsyncAws\Firehose\ValueObject\Record;

class FirehoseClientTest extends TestCase
{
    public function testPutRecord(): void
    {
        self::markTestIncomplete('Cannot test PutRecord without the ability to create firehoses available.');

        $client = $this->getClient();

        $input = new PutRecordInput([
            'DeliveryStreamName' => 'change me',
            'Record' => new Record([
                'Data' => 'change me',
            ]),
        ]);
        $result = $client->putRecord($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getRecordId());
        self::assertFalse($result->getEncrypted());
    }

    public function testPutRecordBatch(): void
    {
        self::markTestIncomplete('Cannot test PutRecord without the ability to create firehoses available.');

        $client = $this->getClient();

        $input = new PutRecordBatchInput([
            'DeliveryStreamName' => 'change me',
            'Records' => [new Record([
                'Data' => 'change me',
            ])],
        ]);
        $result = $client->putRecordBatch($input);

        $result->resolve();

        self::assertSame(1337, $result->getFailedPutCount());
        self::assertFalse($result->getEncrypted());
        // self::assertTODO(expected, $result->getRequestResponses());
    }

    private function getClient(): FirehoseClient
    {
        self::fail('Not implemented');

        return new FirehoseClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
