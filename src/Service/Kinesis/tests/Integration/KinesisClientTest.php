<?php

namespace AsyncAws\Kinesis\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Input\AddTagsToStreamInput;
use AsyncAws\Kinesis\Input\CreateStreamInput;
use AsyncAws\Kinesis\Input\DecreaseStreamRetentionPeriodInput;
use AsyncAws\Kinesis\Input\DeleteStreamInput;
use AsyncAws\Kinesis\Input\DeregisterStreamConsumerInput;
use AsyncAws\Kinesis\Input\DescribeLimitsInput;
use AsyncAws\Kinesis\Input\DescribeStreamConsumerInput;
use AsyncAws\Kinesis\Input\DescribeStreamSummaryInput;
use AsyncAws\Kinesis\Input\DisableEnhancedMonitoringInput;
use AsyncAws\Kinesis\Input\EnableEnhancedMonitoringInput;
use AsyncAws\Kinesis\Input\GetRecordsInput;
use AsyncAws\Kinesis\Input\GetShardIteratorInput;
use AsyncAws\Kinesis\Input\IncreaseStreamRetentionPeriodInput;
use AsyncAws\Kinesis\Input\ListShardsInput;
use AsyncAws\Kinesis\Input\ListStreamConsumersInput;
use AsyncAws\Kinesis\Input\ListStreamsInput;
use AsyncAws\Kinesis\Input\ListTagsForStreamInput;
use AsyncAws\Kinesis\Input\MergeShardsInput;
use AsyncAws\Kinesis\Input\PutRecordInput;
use AsyncAws\Kinesis\Input\PutRecordsInput;
use AsyncAws\Kinesis\Input\RegisterStreamConsumerInput;
use AsyncAws\Kinesis\Input\RemoveTagsFromStreamInput;
use AsyncAws\Kinesis\Input\SplitShardInput;
use AsyncAws\Kinesis\Input\StartStreamEncryptionInput;
use AsyncAws\Kinesis\Input\StopStreamEncryptionInput;
use AsyncAws\Kinesis\Input\UpdateShardCountInput;
use AsyncAws\Kinesis\KinesisClient;
use AsyncAws\Kinesis\ValueObject\PutRecordsRequestEntry;

class KinesisClientTest extends TestCase
{
    public function testAddTagsToStream(): void
    {
        $client = $this->getClient();

        $input = new AddTagsToStreamInput([
            'StreamName' => 'change me',
            'Tags' => ['change me' => 'change me'],
        ]);
        $result = $client->AddTagsToStream($input);

        $result->resolve();
    }

    public function testCreateStream(): void
    {
        $client = $this->getClient();

        $input = new CreateStreamInput([
            'StreamName' => 'change me',
            'ShardCount' => 1337,
        ]);
        $result = $client->CreateStream($input);

        $result->resolve();
    }

    public function testDecreaseStreamRetentionPeriod(): void
    {
        $client = $this->getClient();

        $input = new DecreaseStreamRetentionPeriodInput([
            'StreamName' => 'change me',
            'RetentionPeriodHours' => 1337,
        ]);
        $result = $client->DecreaseStreamRetentionPeriod($input);

        $result->resolve();
    }

    public function testDeleteStream(): void
    {
        $client = $this->getClient();

        $input = new DeleteStreamInput([
            'StreamName' => 'change me',
            'EnforceConsumerDeletion' => false,
        ]);
        $result = $client->DeleteStream($input);

        $result->resolve();
    }

    public function testDeregisterStreamConsumer(): void
    {
        $client = $this->getClient();

        $input = new DeregisterStreamConsumerInput([
            'StreamARN' => 'change me',
            'ConsumerName' => 'change me',
            'ConsumerARN' => 'change me',
        ]);
        $result = $client->DeregisterStreamConsumer($input);

        $result->resolve();
    }

    public function testDescribeLimits(): void
    {
        $client = $this->getClient();

        $input = new DescribeLimitsInput([

        ]);
        $result = $client->DescribeLimits($input);

        $result->resolve();

        self::assertSame(1337, $result->getShardLimit());
        self::assertSame(1337, $result->getOpenShardCount());
    }

    public function testDescribeStreamConsumer(): void
    {
        $client = $this->getClient();

        $input = new DescribeStreamConsumerInput([
            'StreamARN' => 'change me',
            'ConsumerName' => 'change me',
            'ConsumerARN' => 'change me',
        ]);
        $result = $client->DescribeStreamConsumer($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getConsumerDescription());
    }

    public function testDescribeStreamSummary(): void
    {
        $client = $this->getClient();

        $input = new DescribeStreamSummaryInput([
            'StreamName' => 'change me',
        ]);
        $result = $client->DescribeStreamSummary($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getStreamDescriptionSummary());
    }

    public function testDisableEnhancedMonitoring(): void
    {
        $client = $this->getClient();

        $input = new DisableEnhancedMonitoringInput([
            'StreamName' => 'change me',
            'ShardLevelMetrics' => ['change me'],
        ]);
        $result = $client->DisableEnhancedMonitoring($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getStreamName());
        // self::assertTODO(expected, $result->getCurrentShardLevelMetrics());
        // self::assertTODO(expected, $result->getDesiredShardLevelMetrics());
    }

    public function testEnableEnhancedMonitoring(): void
    {
        $client = $this->getClient();

        $input = new EnableEnhancedMonitoringInput([
            'StreamName' => 'change me',
            'ShardLevelMetrics' => ['change me'],
        ]);
        $result = $client->EnableEnhancedMonitoring($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getStreamName());
        // self::assertTODO(expected, $result->getCurrentShardLevelMetrics());
        // self::assertTODO(expected, $result->getDesiredShardLevelMetrics());
    }

    public function testGetRecords(): void
    {
        $client = $this->getClient();

        $input = new GetRecordsInput([
            'ShardIterator' => 'change me',
            'Limit' => 1337,
        ]);
        $result = $client->GetRecords($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getRecords());
        self::assertSame('changeIt', $result->getNextShardIterator());
        self::assertSame(1337, $result->getMillisBehindLatest());
    }

    public function testGetShardIterator(): void
    {
        $client = $this->getClient();

        $input = new GetShardIteratorInput([
            'StreamName' => 'change me',
            'ShardId' => 'change me',
            'ShardIteratorType' => 'change me',
            'StartingSequenceNumber' => 'change me',
            'Timestamp' => new \DateTimeImmutable(),
        ]);
        $result = $client->GetShardIterator($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getShardIterator());
    }

    public function testIncreaseStreamRetentionPeriod(): void
    {
        $client = $this->getClient();

        $input = new IncreaseStreamRetentionPeriodInput([
            'StreamName' => 'change me',
            'RetentionPeriodHours' => 1337,
        ]);
        $result = $client->IncreaseStreamRetentionPeriod($input);

        $result->resolve();
    }

    public function testListShards(): void
    {
        $client = $this->getClient();

        $input = new ListShardsInput([
            'StreamName' => 'change me',
            'NextToken' => 'change me',
            'ExclusiveStartShardId' => 'change me',
            'MaxResults' => 1337,
            'StreamCreationTimestamp' => new \DateTimeImmutable(),
        ]);
        $result = $client->ListShards($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getShards());
        self::assertSame('changeIt', $result->getNextToken());
    }

    public function testListStreamConsumers(): void
    {
        $client = $this->getClient();

        $input = new ListStreamConsumersInput([
            'StreamARN' => 'change me',
            'NextToken' => 'change me',
            'MaxResults' => 1337,
            'StreamCreationTimestamp' => new \DateTimeImmutable(),
        ]);
        $result = $client->ListStreamConsumers($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getConsumers());
        self::assertSame('changeIt', $result->getNextToken());
    }

    public function testListStreams(): void
    {
        $client = $this->getClient();

        $input = new ListStreamsInput([
            'Limit' => 1337,
            'ExclusiveStartStreamName' => 'change me',
        ]);
        $result = $client->ListStreams($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getStreamNames());
        self::assertFalse($result->getHasMoreStreams());
    }

    public function testListTagsForStream(): void
    {
        $client = $this->getClient();

        $input = new ListTagsForStreamInput([
            'StreamName' => 'change me',
            'ExclusiveStartTagKey' => 'change me',
            'Limit' => 1337,
        ]);
        $result = $client->ListTagsForStream($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getTags());
        self::assertFalse($result->getHasMoreTags());
    }

    public function testMergeShards(): void
    {
        $client = $this->getClient();

        $input = new MergeShardsInput([
            'StreamName' => 'change me',
            'ShardToMerge' => 'change me',
            'AdjacentShardToMerge' => 'change me',
        ]);
        $result = $client->MergeShards($input);

        $result->resolve();
    }

    public function testPutRecord(): void
    {
        $client = $this->getClient();

        $input = new PutRecordInput([
            'StreamName' => 'change me',
            'Data' => 'change me',
            'PartitionKey' => 'change me',
            'ExplicitHashKey' => 'change me',
            'SequenceNumberForOrdering' => 'change me',
        ]);
        $result = $client->PutRecord($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getShardId());
        self::assertSame('changeIt', $result->getSequenceNumber());
        self::assertSame('changeIt', $result->getEncryptionType());
    }

    public function testPutRecords(): void
    {
        $client = $this->getClient();

        $input = new PutRecordsInput([
            'Records' => [new PutRecordsRequestEntry([
                'Data' => 'change me',
                'ExplicitHashKey' => 'change me',
                'PartitionKey' => 'change me',
            ])],
            'StreamName' => 'change me',
        ]);
        $result = $client->PutRecords($input);

        $result->resolve();

        self::assertSame(1337, $result->getFailedRecordCount());
        // self::assertTODO(expected, $result->getRecords());
        self::assertSame('changeIt', $result->getEncryptionType());
    }

    public function testRegisterStreamConsumer(): void
    {
        $client = $this->getClient();

        $input = new RegisterStreamConsumerInput([
            'StreamARN' => 'change me',
            'ConsumerName' => 'change me',
        ]);
        $result = $client->RegisterStreamConsumer($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getConsumer());
    }

    public function testRemoveTagsFromStream(): void
    {
        $client = $this->getClient();

        $input = new RemoveTagsFromStreamInput([
            'StreamName' => 'change me',
            'TagKeys' => ['change me'],
        ]);
        $result = $client->RemoveTagsFromStream($input);

        $result->resolve();
    }

    public function testSplitShard(): void
    {
        $client = $this->getClient();

        $input = new SplitShardInput([
            'StreamName' => 'change me',
            'ShardToSplit' => 'change me',
            'NewStartingHashKey' => 'change me',
        ]);
        $result = $client->SplitShard($input);

        $result->resolve();
    }

    public function testStartStreamEncryption(): void
    {
        $client = $this->getClient();

        $input = new StartStreamEncryptionInput([
            'StreamName' => 'change me',
            'EncryptionType' => 'change me',
            'KeyId' => 'change me',
        ]);
        $result = $client->StartStreamEncryption($input);

        $result->resolve();
    }

    public function testStopStreamEncryption(): void
    {
        $client = $this->getClient();

        $input = new StopStreamEncryptionInput([
            'StreamName' => 'change me',
            'EncryptionType' => 'change me',
            'KeyId' => 'change me',
        ]);
        $result = $client->StopStreamEncryption($input);

        $result->resolve();
    }

    public function testUpdateShardCount(): void
    {
        $client = $this->getClient();

        $input = new UpdateShardCountInput([
            'StreamName' => 'change me',
            'TargetShardCount' => 1337,
            'ScalingType' => 'change me',
        ]);
        $result = $client->UpdateShardCount($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getStreamName());
        self::assertSame(1337, $result->getCurrentShardCount());
        self::assertSame(1337, $result->getTargetShardCount());
    }

    private function getClient(): KinesisClient
    {
        self::fail('Not implemented');

        return new KinesisClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
