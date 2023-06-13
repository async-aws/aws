<?php

namespace AsyncAws\Kinesis\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Result;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Enum\EncryptionType;
use AsyncAws\Kinesis\Enum\MetricsName;
use AsyncAws\Kinesis\Enum\ScalingType;
use AsyncAws\Kinesis\Enum\ShardIteratorType;
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
use AsyncAws\Kinesis\Result\DescribeLimitsOutput;
use AsyncAws\Kinesis\Result\DescribeStreamConsumerOutput;
use AsyncAws\Kinesis\Result\DescribeStreamOutput;
use AsyncAws\Kinesis\Result\DescribeStreamSummaryOutput;
use AsyncAws\Kinesis\Result\EnhancedMonitoringOutput;
use AsyncAws\Kinesis\Result\GetRecordsOutput;
use AsyncAws\Kinesis\Result\GetShardIteratorOutput;
use AsyncAws\Kinesis\Result\ListShardsOutput;
use AsyncAws\Kinesis\Result\ListStreamConsumersOutput;
use AsyncAws\Kinesis\Result\ListStreamsOutput;
use AsyncAws\Kinesis\Result\ListTagsForStreamOutput;
use AsyncAws\Kinesis\Result\PutRecordOutput;
use AsyncAws\Kinesis\Result\PutRecordsOutput;
use AsyncAws\Kinesis\Result\RegisterStreamConsumerOutput;
use AsyncAws\Kinesis\Result\UpdateShardCountOutput;
use AsyncAws\Kinesis\ValueObject\PutRecordsRequestEntry;
use Symfony\Component\HttpClient\MockHttpClient;

class KinesisClientTest extends TestCase
{
    public function testAddTagsToStream(): void
    {
        $client = new KinesisClient([], new NullProvider(), new MockHttpClient());

        $input = new AddTagsToStreamInput([
            'StreamName' => 'change me',
            'Tags' => ['change me' => 'change me'],
        ]);
        $result = $client->addTagsToStream($input);

        self::assertInstanceOf(Result::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testCreateStream(): void
    {
        $client = new KinesisClient([], new NullProvider(), new MockHttpClient());

        $input = new CreateStreamInput([
            'StreamName' => 'change me',
            'ShardCount' => 1337,
        ]);
        $result = $client->createStream($input);

        self::assertInstanceOf(Result::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDecreaseStreamRetentionPeriod(): void
    {
        $client = new KinesisClient([], new NullProvider(), new MockHttpClient());

        $input = new DecreaseStreamRetentionPeriodInput([
            'StreamName' => 'change me',
            'RetentionPeriodHours' => 1337,
        ]);
        $result = $client->decreaseStreamRetentionPeriod($input);

        self::assertInstanceOf(Result::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDeleteStream(): void
    {
        $client = new KinesisClient([], new NullProvider(), new MockHttpClient());

        $input = new DeleteStreamInput([
            'StreamName' => 'change me',
        ]);
        $result = $client->deleteStream($input);

        self::assertInstanceOf(Result::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDeregisterStreamConsumer(): void
    {
        $client = new KinesisClient([], new NullProvider(), new MockHttpClient());

        $input = new DeregisterStreamConsumerInput([
        ]);
        $result = $client->deregisterStreamConsumer($input);

        self::assertInstanceOf(Result::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDescribeLimits(): void
    {
        $client = new KinesisClient([], new NullProvider(), new MockHttpClient());

        $input = new DescribeLimitsInput([
        ]);
        $result = $client->describeLimits($input);

        self::assertInstanceOf(DescribeLimitsOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDescribeStream(): void
    {
        $client = new KinesisClient([], new NullProvider(), new MockHttpClient());

        $input = new DescribeStreamInput([
            'StreamName' => 'change me',
        ]);
        $result = $client->describeStream($input);

        self::assertInstanceOf(DescribeStreamOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDescribeStreamConsumer(): void
    {
        $client = new KinesisClient([], new NullProvider(), new MockHttpClient());

        $input = new DescribeStreamConsumerInput([
        ]);
        $result = $client->describeStreamConsumer($input);

        self::assertInstanceOf(DescribeStreamConsumerOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDescribeStreamSummary(): void
    {
        $client = new KinesisClient([], new NullProvider(), new MockHttpClient());

        $input = new DescribeStreamSummaryInput([
            'StreamName' => 'change me',
        ]);
        $result = $client->describeStreamSummary($input);

        self::assertInstanceOf(DescribeStreamSummaryOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDisableEnhancedMonitoring(): void
    {
        $client = new KinesisClient([], new NullProvider(), new MockHttpClient());

        $input = new DisableEnhancedMonitoringInput([
            'StreamName' => 'change me',
            'ShardLevelMetrics' => [MetricsName::OUTGOING_RECORDS],
        ]);
        $result = $client->disableEnhancedMonitoring($input);

        self::assertInstanceOf(EnhancedMonitoringOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testEnableEnhancedMonitoring(): void
    {
        $client = new KinesisClient([], new NullProvider(), new MockHttpClient());

        $input = new EnableEnhancedMonitoringInput([
            'StreamName' => 'change me',
            'ShardLevelMetrics' => [MetricsName::OUTGOING_RECORDS],
        ]);
        $result = $client->enableEnhancedMonitoring($input);

        self::assertInstanceOf(EnhancedMonitoringOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetRecords(): void
    {
        $client = new KinesisClient([], new NullProvider(), new MockHttpClient());

        $input = new GetRecordsInput([
            'ShardIterator' => 'change me',
        ]);
        $result = $client->getRecords($input);

        self::assertInstanceOf(GetRecordsOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetShardIterator(): void
    {
        $client = new KinesisClient([], new NullProvider(), new MockHttpClient());

        $input = new GetShardIteratorInput([
            'StreamName' => 'change me',
            'ShardId' => 'change me',
            'ShardIteratorType' => ShardIteratorType::LATEST,
        ]);
        $result = $client->getShardIterator($input);

        self::assertInstanceOf(GetShardIteratorOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testIncreaseStreamRetentionPeriod(): void
    {
        $client = new KinesisClient([], new NullProvider(), new MockHttpClient());

        $input = new IncreaseStreamRetentionPeriodInput([
            'StreamName' => 'change me',
            'RetentionPeriodHours' => 1337,
        ]);
        $result = $client->increaseStreamRetentionPeriod($input);

        self::assertInstanceOf(Result::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListShards(): void
    {
        $client = new KinesisClient([], new NullProvider(), new MockHttpClient());

        $input = new ListShardsInput([
        ]);
        $result = $client->listShards($input);

        self::assertInstanceOf(ListShardsOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListStreamConsumers(): void
    {
        $client = new KinesisClient([], new NullProvider(), new MockHttpClient());

        $input = new ListStreamConsumersInput([
            'StreamARN' => 'change me',
        ]);
        $result = $client->listStreamConsumers($input);

        self::assertInstanceOf(ListStreamConsumersOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListStreams(): void
    {
        $client = new KinesisClient([], new NullProvider(), new MockHttpClient());

        $input = new ListStreamsInput([
        ]);
        $result = $client->listStreams($input);

        self::assertInstanceOf(ListStreamsOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListTagsForStream(): void
    {
        $client = new KinesisClient([], new NullProvider(), new MockHttpClient());

        $input = new ListTagsForStreamInput([
            'StreamName' => 'change me',
        ]);
        $result = $client->listTagsForStream($input);

        self::assertInstanceOf(ListTagsForStreamOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testMergeShards(): void
    {
        $client = new KinesisClient([], new NullProvider(), new MockHttpClient());

        $input = new MergeShardsInput([
            'StreamName' => 'change me',
            'ShardToMerge' => 'change me',
            'AdjacentShardToMerge' => 'change me',
        ]);
        $result = $client->mergeShards($input);

        self::assertInstanceOf(Result::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testPutRecord(): void
    {
        $client = new KinesisClient([], new NullProvider(), new MockHttpClient());

        $input = new PutRecordInput([
            'StreamName' => 'change me',
            'Data' => 'change me',
            'PartitionKey' => 'change me',
        ]);
        $result = $client->putRecord($input);

        self::assertInstanceOf(PutRecordOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testPutRecords(): void
    {
        $client = new KinesisClient([], new NullProvider(), new MockHttpClient());

        $input = new PutRecordsInput([
            'Records' => [new PutRecordsRequestEntry([
                'Data' => 'change me',

                'PartitionKey' => 'change me',
            ])],
            'StreamName' => 'change me',
        ]);
        $result = $client->putRecords($input);

        self::assertInstanceOf(PutRecordsOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testRegisterStreamConsumer(): void
    {
        $client = new KinesisClient([], new NullProvider(), new MockHttpClient());

        $input = new RegisterStreamConsumerInput([
            'StreamARN' => 'change me',
            'ConsumerName' => 'change me',
        ]);
        $result = $client->registerStreamConsumer($input);

        self::assertInstanceOf(RegisterStreamConsumerOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testRemoveTagsFromStream(): void
    {
        $client = new KinesisClient([], new NullProvider(), new MockHttpClient());

        $input = new RemoveTagsFromStreamInput([
            'StreamName' => 'change me',
            'TagKeys' => ['change me'],
        ]);
        $result = $client->removeTagsFromStream($input);

        self::assertInstanceOf(Result::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testSplitShard(): void
    {
        $client = new KinesisClient([], new NullProvider(), new MockHttpClient());

        $input = new SplitShardInput([
            'StreamName' => 'change me',
            'ShardToSplit' => 'change me',
            'NewStartingHashKey' => 'change me',
        ]);
        $result = $client->splitShard($input);

        self::assertInstanceOf(Result::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testStartStreamEncryption(): void
    {
        $client = new KinesisClient([], new NullProvider(), new MockHttpClient());

        $input = new StartStreamEncryptionInput([
            'StreamName' => 'change me',
            'EncryptionType' => EncryptionType::KMS,
            'KeyId' => 'change me',
        ]);
        $result = $client->startStreamEncryption($input);

        self::assertInstanceOf(Result::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testStopStreamEncryption(): void
    {
        $client = new KinesisClient([], new NullProvider(), new MockHttpClient());

        $input = new StopStreamEncryptionInput([
            'StreamName' => 'change me',
            'EncryptionType' => EncryptionType::KMS,
            'KeyId' => 'change me',
        ]);
        $result = $client->stopStreamEncryption($input);

        self::assertInstanceOf(Result::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testUpdateShardCount(): void
    {
        $client = new KinesisClient([], new NullProvider(), new MockHttpClient());

        $input = new UpdateShardCountInput([
            'StreamName' => 'change me',
            'TargetShardCount' => 1337,
            'ScalingType' => ScalingType::UNIFORM_SCALING,
        ]);
        $result = $client->updateShardCount($input);

        self::assertInstanceOf(UpdateShardCountOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
