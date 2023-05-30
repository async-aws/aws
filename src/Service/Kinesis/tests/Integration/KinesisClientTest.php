<?php

namespace AsyncAws\Kinesis\Tests\Integration;

use AsyncAws\Core\Credentials\Credentials;
use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Enum\EncryptionType;
use AsyncAws\Kinesis\Enum\MetricsName;
use AsyncAws\Kinesis\Enum\ShardIteratorType;
use AsyncAws\Kinesis\Enum\StreamStatus;
use AsyncAws\Kinesis\Exception\ResourceNotFoundException;
use AsyncAws\Kinesis\Input\AddTagsToStreamInput;
use AsyncAws\Kinesis\Input\CreateStreamInput;
use AsyncAws\Kinesis\Input\DecreaseStreamRetentionPeriodInput;
use AsyncAws\Kinesis\Input\DeleteStreamInput;
use AsyncAws\Kinesis\Input\DeregisterStreamConsumerInput;
use AsyncAws\Kinesis\Input\DescribeLimitsInput;
use AsyncAws\Kinesis\Input\DescribeStreamConsumerInput;
use AsyncAws\Kinesis\Input\DescribeStreamInput;
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
        $this->cleanup(__FUNCTION__);
        $client = $this->getClient();
        $client->createStream(['StreamName' => __FUNCTION__, 'ShardCount' => 1]);

        $input = new AddTagsToStreamInput([
            'StreamName' => __FUNCTION__,
            'Tags' => ['Project' => 'myProject'],
        ]);
        $result = $client->addTagsToStream($input);

        self::assertTrue($result->resolve());
    }

    public function testCreateStream(): void
    {
        $this->cleanup(__FUNCTION__);
        $client = $this->getClient();

        $input = new CreateStreamInput([
            'StreamName' => __FUNCTION__,
            'ShardCount' => 2,
        ]);
        $result = $client->createStream($input);

        self::assertTrue($result->resolve());
    }

    public function testDecreaseStreamRetentionPeriod(): void
    {
        $this->cleanup(__FUNCTION__);
        $client = $this->getClient();
        $client->createStream(['StreamName' => __FUNCTION__, 'ShardCount' => 1]);
        $client->streamExists(['StreamName' => __FUNCTION__])->wait(null, 0.2);

        $input = new DecreaseStreamRetentionPeriodInput([
            'StreamName' => __FUNCTION__,
            'RetentionPeriodHours' => 24,
        ]);
        $result = $client->decreaseStreamRetentionPeriod($input);

        self::assertTrue($result->resolve());
    }

    public function testDeleteStream(): void
    {
        $this->cleanup(__FUNCTION__);
        $client = $this->getClient();
        $client->createStream(['StreamName' => __FUNCTION__, 'ShardCount' => 1]);
        $client->streamExists(['StreamName' => __FUNCTION__])->wait(null, 0.2);

        $input = new DeleteStreamInput([
            'StreamName' => __FUNCTION__,
            'EnforceConsumerDeletion' => false,
        ]);
        $result = $client->deleteStream($input);

        self::assertTrue($result->resolve());
    }

    public function testDeregisterStreamConsumer(): void
    {
        $this->cleanup(__FUNCTION__);
        $client = $this->getClient();
        $client->createStream(['StreamName' => __FUNCTION__, 'ShardCount' => 1]);
        $client->streamExists(['StreamName' => __FUNCTION__])->wait(null, 0.2);
        $stream = $client->describeStream(['StreamName' => __FUNCTION__]);
        $client->registerStreamConsumer(['StreamARN' => $stream->getStreamDescription()->getStreamArn(), 'ConsumerName' => 'demo']);
        usleep(500000);
        $input = new DeregisterStreamConsumerInput([
            'StreamARN' => $stream->getStreamDescription()->getStreamArn(),
            'ConsumerName' => 'demo',
        ]);
        $result = $client->deregisterStreamConsumer($input);

        self::assertTrue($result->resolve());
    }

    public function testDescribeLimits(): void
    {
        self::markTestSkipped('The Kinesis Docker image does not implement DescribeLimits.');

        $this->cleanup(__FUNCTION__);
        $client = $this->getClient();
        $client->createStream(['StreamName' => __FUNCTION__, 'ShardCount' => 1]);
        $client->streamExists(['StreamName' => __FUNCTION__])->wait(null, 0.2);

        $input = new DescribeLimitsInput([]);
        $result = $client->describeLimits($input);

        $result->resolve();

        self::assertSame(1, $result->getShardLimit());
        self::assertSame(1, $result->getOpenShardCount());
    }

    public function testDescribeStream(): void
    {
        $this->cleanup(__FUNCTION__);
        $client = $this->getClient();
        $client->createStream(['StreamName' => __FUNCTION__, 'ShardCount' => 1]);
        $client->streamExists(['StreamName' => __FUNCTION__])->wait(null, 0.2);

        $input = new DescribeStreamInput([
            'StreamName' => __FUNCTION__,
        ]);
        $result = $client->describeStream($input);

        $result->resolve();

        self::assertSame(__FUNCTION__, $result->getStreamDescription()->getStreamName());
        self::assertSame(24, $result->getStreamDescription()->getRetentionPeriodHours());
    }

    public function testDescribeStreamConsumer(): void
    {
        $this->cleanup(__FUNCTION__);
        $client = $this->getClient();
        $client->createStream(['StreamName' => __FUNCTION__, 'ShardCount' => 1]);
        $client->streamExists(['StreamName' => __FUNCTION__])->wait(null, 0.2);
        $stream = $client->describeStream(['StreamName' => __FUNCTION__]);
        $client->registerStreamConsumer(['StreamARN' => $stream->getStreamDescription()->getStreamArn(), 'ConsumerName' => 'demo']);

        $input = new DescribeStreamConsumerInput([
            'StreamARN' => $stream->getStreamDescription()->getStreamArn(),
            'ConsumerName' => 'demo',
        ]);
        $result = $client->describeStreamConsumer($input);

        $result->resolve();

        self::assertSame('demo', $result->getConsumerDescription()->getConsumerName());
    }

    public function testDescribeStreamSummary(): void
    {
        $this->cleanup(__FUNCTION__);
        $client = $this->getClient();
        $client->createStream(['StreamName' => __FUNCTION__, 'ShardCount' => 1]);
        $client->streamExists(['StreamName' => __FUNCTION__])->wait(null, 0.2);

        $input = new DescribeStreamSummaryInput([
            'StreamName' => __FUNCTION__,
        ]);
        $result = $client->describeStreamSummary($input);

        $result->resolve();

        self::assertSame(__FUNCTION__, $result->getStreamDescriptionSummary()->getStreamName());
        self::assertSame(24, $result->getStreamDescriptionSummary()->getRetentionPeriodHours());
    }

    public function testDisableEnhancedMonitoring(): void
    {
        self::markTestSkipped('The Kinesis Docker image does not implement DisableEnhancedMonitoring.');

        $this->cleanup(__FUNCTION__);
        $client = $this->getClient();
        $client->createStream(['StreamName' => __FUNCTION__, 'ShardCount' => 1]);
        $client->streamExists(['StreamName' => __FUNCTION__])->wait(null, 0.2);

        $input = new DisableEnhancedMonitoringInput([
            'StreamName' => __FUNCTION__,
            'ShardLevelMetrics' => [MetricsName::ALL],
        ]);
        $result = $client->disableEnhancedMonitoring($input);

        $result->resolve();

        self::assertSame(__FUNCTION__, $result->getStreamName());
        self::assertSame(MetricsName::ALL, $result->getCurrentShardLevelMetrics());
    }

    public function testEnableEnhancedMonitoring(): void
    {
        self::markTestSkipped('The Kinesis Docker image does not implement DisableEnhancedMonitoring.');

        $this->cleanup(__FUNCTION__);
        $client = $this->getClient();
        $client->createStream(['StreamName' => __FUNCTION__, 'ShardCount' => 1]);
        $client->streamExists(['StreamName' => __FUNCTION__])->wait(null, 0.2);

        $input = new EnableEnhancedMonitoringInput([
            'StreamName' => __FUNCTION__,
            'ShardLevelMetrics' => [MetricsName::ALL],
        ]);
        $result = $client->enableEnhancedMonitoring($input);

        $result->resolve();

        self::assertSame(__FUNCTION__, $result->getStreamName());
        self::assertSame(MetricsName::ALL, $result->getCurrentShardLevelMetrics());
    }

    public function testGetRecords(): void
    {
        $client = $this->getClient();
        $this->createStream(__FUNCTION__);
        $client->streamExists(['StreamName' => __FUNCTION__])->wait(null, 0.2);
        $shardIterator = $client->getShardIterator(['StreamName' => __FUNCTION__, 'ShardId' => 'shardId-000000000000', 'ShardIteratorType' => ShardIteratorType::TRIM_HORIZON])->getShardIterator();

        $client->putRecord([
            'StreamName' => __FUNCTION__,
            'Data' => 'data',
            'PartitionKey' => 'key',
        ]);

        $input = new GetRecordsInput([
            'ShardIterator' => $shardIterator,
            'Limit' => 1,
        ]);
        $result = $client->getRecords($input);

        $result->resolve();

        self::assertCount(1, $result->getRecords());
        self::assertSame('data', $result->getRecords()[0]->getData());
    }

    public function testGetShardIterator(): void
    {
        $client = $this->getClient();
        $this->createStream(__FUNCTION__);
        $client->streamExists(['StreamName' => __FUNCTION__])->wait(null, 0.2);

        $input = new GetShardIteratorInput([
            'StreamName' => __FUNCTION__,
            'ShardId' => 'shardId-000000000000',
            'ShardIteratorType' => ShardIteratorType::TRIM_HORIZON,
        ]);
        $result = $client->getShardIterator($input);

        self::assertTrue($result->resolve());
    }

    public function testIncreaseStreamRetentionPeriod(): void
    {
        $this->cleanup(__FUNCTION__);
        $client = $this->getClient();
        $client->createStream(['StreamName' => __FUNCTION__, 'ShardCount' => 1]);
        $client->streamExists(['StreamName' => __FUNCTION__])->wait(null, 0.2);

        $input = new IncreaseStreamRetentionPeriodInput([
            'StreamName' => __FUNCTION__,
            'RetentionPeriodHours' => 50,
        ]);
        $result = $client->increaseStreamRetentionPeriod($input);

        self::assertTrue($result->resolve());
    }

    public function testListShards(): void
    {
        $client = $this->getClient();
        $this->createStream(__FUNCTION__);
        $client->streamExists(['StreamName' => __FUNCTION__])->wait(null, 0.2);

        $input = new ListShardsInput([
            'StreamName' => __FUNCTION__,
        ]);
        $result = $client->listShards($input);

        $result->resolve();

        self::assertCount(1, $result->getShards());
        self::assertSame('shardId-000000000000', $result->getShards()[0]->getShardId());
    }

    public function testListStreamConsumers(): void
    {
        $this->cleanup(__FUNCTION__);
        $client = $this->getClient();
        $client->createStream(['StreamName' => __FUNCTION__, 'ShardCount' => 1]);
        $client->streamExists(['StreamName' => __FUNCTION__])->wait(null, 0.2);
        $stream = $client->describeStream(['StreamName' => __FUNCTION__]);
        $client->registerStreamConsumer(['StreamARN' => $stream->getStreamDescription()->getStreamArn(), 'ConsumerName' => 'demo']);

        $input = new ListStreamConsumersInput([
            'StreamARN' => $stream->getStreamDescription()->getStreamArn(),
        ]);
        $result = $client->listStreamConsumers($input);

        $result->resolve();

        $consumers = iterator_to_array($result->getConsumers());
        self::assertCount(1, $consumers);
        self::assertSame('demo', $consumers[0]->getConsumerName());
    }

    public function testListStreams(): void
    {
        $this->cleanup(__FUNCTION__);
        $client = $this->getClient();
        $client->createStream(['StreamName' => __FUNCTION__, 'ShardCount' => 1]);
        $client->streamExists(['StreamName' => __FUNCTION__])->wait(null, 0.2);

        $input = new ListStreamsInput([]);
        $result = $client->listStreams($input);

        $streamNames = iterator_to_array($result->getStreamNames());
        self::assertGreaterThanOrEqual(1, \count($streamNames));
        self::assertContains(__FUNCTION__, $streamNames);
    }

    public function testListTagsForStream(): void
    {
        $this->cleanup(__FUNCTION__);
        $client = $this->getClient();
        $client->createStream(['StreamName' => __FUNCTION__, 'ShardCount' => 1]);
        $client->streamExists(['StreamName' => __FUNCTION__])->wait(null, 0.2);
        $client->addTagsToStream(['StreamName' => __FUNCTION__, 'Tags' => ['Project' => 'myProject']]);

        $input = new ListTagsForStreamInput([
            'StreamName' => __FUNCTION__,
        ]);
        $result = $client->listTagsForStream($input);

        $result->resolve();

        self::assertCount(1, $result->getTags());
        self::assertSame('Project', $result->getTags()[0]->getKey());
        self::assertFalse($result->getHasMoreTags());
    }

    public function testMergeShards(): void
    {
        self::markTestSkipped('The Kinesis Docker image does not implement MergeShards.');

        $client = $this->getClient();

        $input = new MergeShardsInput([
            'StreamName' => __FUNCTION__,
            'ShardToMerge' => 'change me',
            'AdjacentShardToMerge' => 'change me',
        ]);
        $result = $client->mergeShards($input);

        $result->resolve();
    }

    public function testPutRecord(): void
    {
        $client = $this->getClient();
        $this->createStream(__FUNCTION__);
        $client->streamExists(['StreamName' => __FUNCTION__])->wait(null, 0.2);

        $input = new PutRecordInput([
            'StreamName' => __FUNCTION__,
            'Data' => 'data',
            'PartitionKey' => 'key',
        ]);
        $result = $client->putRecord($input);

        $result->resolve();

        self::assertSame('shardId-000000000000', $result->getShardId());
    }

    public function testPutRecords(): void
    {
        $client = $this->getClient();
        $this->createStream(__FUNCTION__);
        $client->streamExists(['StreamName' => __FUNCTION__])->wait(null, 0.2);

        $input = new PutRecordsInput([
            'StreamName' => __FUNCTION__,
            'Records' => [new PutRecordsRequestEntry([
                'Data' => 'data1',
                'PartitionKey' => 'key',
            ]), new PutRecordsRequestEntry([
                'Data' => 'data2',
                'PartitionKey' => 'key',
            ])],
        ]);
        $result = $client->putRecords($input);

        $result->resolve();

        self::assertCount(2, $result->getRecords());
        self::assertSame(0, $result->getFailedRecordCount());
    }

    public function testRegisterStreamConsumer(): void
    {
        $this->cleanup(__FUNCTION__);
        $client = $this->getClient();
        $client->createStream(['StreamName' => __FUNCTION__, 'ShardCount' => 1]);
        $client->streamExists(['StreamName' => __FUNCTION__])->wait(null, 0.2);
        $stream = $client->describeStream(['StreamName' => __FUNCTION__]);

        $client = $this->getClient();

        $input = new RegisterStreamConsumerInput([
            'StreamARN' => $stream->getStreamDescription()->getStreamArn(),
            'ConsumerName' => 'demo',
        ]);
        $result = $client->registerStreamConsumer($input);

        $result->resolve();

        self::assertSame('demo', $result->getConsumer()->getConsumerName());
    }

    public function testRemoveTagsFromStream(): void
    {
        $this->cleanup(__FUNCTION__);
        $client = $this->getClient();
        $client->createStream(['StreamName' => __FUNCTION__, 'ShardCount' => 1]);
        $client->streamExists(['StreamName' => __FUNCTION__])->wait(null, 0.2);
        $client->addTagsToStream(['StreamName' => __FUNCTION__, 'Tags' => ['Project' => 'myProject']]);

        self::assertCount(1, $client->listTagsForStream(['StreamName' => __FUNCTION__])->getTags());

        $client = $this->getClient();

        $input = new RemoveTagsFromStreamInput([
            'StreamName' => __FUNCTION__,
            'TagKeys' => ['Project'],
        ]);
        $result = $client->removeTagsFromStream($input);

        $result->resolve();

        self::assertCount(0, $client->listTagsForStream(['StreamName' => __FUNCTION__])->getTags());
    }

    public function testSplitShard(): void
    {
        $client = $this->getClient();
        $this->createStream(__FUNCTION__);
        $client->streamExists(['StreamName' => __FUNCTION__])->wait(null, 0.2);

        $input = new SplitShardInput([
            'StreamName' => __FUNCTION__,
            'ShardToSplit' => 'shardId-000000000000',
            'NewStartingHashKey' => '10',
        ]);
        $result = $client->splitShard($input);

        self::assertTrue($result->resolve());
    }

    public function testStartStreamEncryption(): void
    {
        self::markTestSkipped('The Kinesis Docker image does not implement StartStreamEncryption.');

        $client = $this->getClient();

        $input = new StartStreamEncryptionInput([
            'StreamName' => __FUNCTION__,
            'EncryptionType' => EncryptionType::NONE,
            'KeyId' => 'key',
        ]);
        $result = $client->startStreamEncryption($input);

        self::assertTrue($result->resolve());
    }

    public function testStopStreamEncryption(): void
    {
        self::markTestSkipped('The Kinesis Docker image does not implement StopStreamEncryption.');

        $client = $this->getClient();

        $input = new StopStreamEncryptionInput([
            'StreamName' => __FUNCTION__,
            'EncryptionType' => EncryptionType::NONE,
            'KeyId' => 'key',
        ]);
        $result = $client->stopStreamEncryption($input);

        self::assertTrue($result->resolve());
    }

    public function testUpdateShardCount(): void
    {
        self::markTestSkipped('The Kinesis Docker image does not implements UpdateShardCountInput.');

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

    private function cleanup(string $streamName): void
    {
        $client = $this->getClient();

        try {
            $stream = $client->describeStream(['StreamName' => $streamName]);

            $cpt = 0;
            while ($cpt++ < 10 && \count(iterator_to_array($client->listStreamConsumers(['StreamARN' => $stream->getStreamDescription()->getStreamArn()]))) > 0) {
                try {
                    $client->deregisterStreamConsumer(['StreamARN' => $stream->getStreamDescription()->getStreamArn(), 'ConsumerName' => 'demo']);
                    usleep(200000);
                } catch (ClientException $e) {
                    usleep(500000);
                }
            }
            $client->deleteStream(['StreamName' => $streamName]);
            $client->streamNotExists(['StreamName' => $streamName])->wait(null, 0.2);
        } catch (ResourceNotFoundException $e) {
        }
        usleep(500000);
    }

    private function createStream(string $streamName): void
    {
        $this->cleanup($streamName);
        $client = $this->getClient();
        $client->createStream(['StreamName' => $streamName, 'ShardCount' => 1]);
        $client->streamExists(['StreamName' => __FUNCTION__])->wait(null, 0.2);

        while (true) {
            $stream = $client->describeStream(['StreamName' => $streamName]);
            if (StreamStatus::CREATING !== $stream->getStreamDescription()->getStreamStatus()) {
                return;
            }
            usleep(100000);
        }
    }

    private function getClient(): KinesisClient
    {
        return new KinesisClient([
            'endpoint' => 'http://localhost:4578',
        ], new Credentials('aws_id', 'aws_secret'));
    }
}
