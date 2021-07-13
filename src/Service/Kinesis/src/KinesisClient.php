<?php

namespace AsyncAws\Kinesis;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\Core\Result;
use AsyncAws\Kinesis\Enum\EncryptionType;
use AsyncAws\Kinesis\Enum\MetricsName;
use AsyncAws\Kinesis\Enum\ScalingType;
use AsyncAws\Kinesis\Enum\ShardIteratorType;
use AsyncAws\Kinesis\Exception\ExpiredIteratorException;
use AsyncAws\Kinesis\Exception\ExpiredNextTokenException;
use AsyncAws\Kinesis\Exception\InvalidArgumentException;
use AsyncAws\Kinesis\Exception\KMSAccessDeniedException;
use AsyncAws\Kinesis\Exception\KMSDisabledException;
use AsyncAws\Kinesis\Exception\KMSInvalidStateException;
use AsyncAws\Kinesis\Exception\KMSNotFoundException;
use AsyncAws\Kinesis\Exception\KMSOptInRequiredException;
use AsyncAws\Kinesis\Exception\KMSThrottlingException;
use AsyncAws\Kinesis\Exception\LimitExceededException;
use AsyncAws\Kinesis\Exception\ProvisionedThroughputExceededException;
use AsyncAws\Kinesis\Exception\ResourceInUseException;
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
use AsyncAws\Kinesis\Result\StreamExistsWaiter;
use AsyncAws\Kinesis\Result\StreamNotExistsWaiter;
use AsyncAws\Kinesis\Result\UpdateShardCountOutput;
use AsyncAws\Kinesis\ValueObject\PutRecordsRequestEntry;

class KinesisClient extends AbstractApi
{
    /**
     * Adds or updates tags for the specified Kinesis data stream. Each time you invoke this operation, you can specify up
     * to 10 tags. If you want to add more than 10 tags to your stream, you can invoke this operation multiple times. In
     * total, each stream can have up to 50 tags.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_AddTagsToStream.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#addtagstostream
     *
     * @param array{
     *   StreamName: string,
     *   Tags: array<string, string>,
     *   @region?: string,
     * }|AddTagsToStreamInput $input
     *
     * @throws ResourceNotFoundException
     * @throws ResourceInUseException
     * @throws InvalidArgumentException
     * @throws LimitExceededException
     */
    public function addTagsToStream($input): Result
    {
        $input = AddTagsToStreamInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AddTagsToStream', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ResourceInUseException' => ResourceInUseException::class,
            'InvalidArgumentException' => InvalidArgumentException::class,
            'LimitExceededException' => LimitExceededException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Creates a Kinesis data stream. A stream captures and transports data records that are continuously emitted from
     * different data sources or *producers*. Scale-out within a stream is explicitly supported by means of shards, which
     * are uniquely identified groups of data records in a stream.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_CreateStream.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#createstream
     *
     * @param array{
     *   StreamName: string,
     *   ShardCount: int,
     *   @region?: string,
     * }|CreateStreamInput $input
     *
     * @throws ResourceInUseException
     * @throws LimitExceededException
     * @throws InvalidArgumentException
     */
    public function createStream($input): Result
    {
        $input = CreateStreamInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateStream', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceInUseException' => ResourceInUseException::class,
            'LimitExceededException' => LimitExceededException::class,
            'InvalidArgumentException' => InvalidArgumentException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Decreases the Kinesis data stream's retention period, which is the length of time data records are accessible after
     * they are added to the stream. The minimum value of a stream's retention period is 24 hours.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_DecreaseStreamRetentionPeriod.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#decreasestreamretentionperiod
     *
     * @param array{
     *   StreamName: string,
     *   RetentionPeriodHours: int,
     *   @region?: string,
     * }|DecreaseStreamRetentionPeriodInput $input
     *
     * @throws ResourceInUseException
     * @throws ResourceNotFoundException
     * @throws LimitExceededException
     * @throws InvalidArgumentException
     */
    public function decreaseStreamRetentionPeriod($input): Result
    {
        $input = DecreaseStreamRetentionPeriodInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DecreaseStreamRetentionPeriod', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceInUseException' => ResourceInUseException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'LimitExceededException' => LimitExceededException::class,
            'InvalidArgumentException' => InvalidArgumentException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Deletes a Kinesis data stream and all its shards and data. You must shut down any applications that are operating on
     * the stream before you delete the stream. If an application attempts to operate on a deleted stream, it receives the
     * exception `ResourceNotFoundException`.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_DeleteStream.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#deletestream
     *
     * @param array{
     *   StreamName: string,
     *   EnforceConsumerDeletion?: bool,
     *   @region?: string,
     * }|DeleteStreamInput $input
     *
     * @throws ResourceNotFoundException
     * @throws LimitExceededException
     * @throws ResourceInUseException
     */
    public function deleteStream($input): Result
    {
        $input = DeleteStreamInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteStream', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'LimitExceededException' => LimitExceededException::class,
            'ResourceInUseException' => ResourceInUseException::class,
        ]]));

        return new Result($response);
    }

    /**
     * To deregister a consumer, provide its ARN. Alternatively, you can provide the ARN of the data stream and the name you
     * gave the consumer when you registered it. You may also provide all three parameters, as long as they don't conflict
     * with each other. If you don't know the name or ARN of the consumer that you want to deregister, you can use the
     * ListStreamConsumers operation to get a list of the descriptions of all the consumers that are currently registered
     * with a given data stream. The description of a consumer contains its name and ARN.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_DeregisterStreamConsumer.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#deregisterstreamconsumer
     *
     * @param array{
     *   StreamARN?: string,
     *   ConsumerName?: string,
     *   ConsumerARN?: string,
     *   @region?: string,
     * }|DeregisterStreamConsumerInput $input
     *
     * @throws LimitExceededException
     * @throws ResourceNotFoundException
     * @throws InvalidArgumentException
     */
    public function deregisterStreamConsumer($input = []): Result
    {
        $input = DeregisterStreamConsumerInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeregisterStreamConsumer', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'LimitExceededException' => LimitExceededException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidArgumentException' => InvalidArgumentException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Describes the shard limits and usage for the account.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_DescribeLimits.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#describelimits
     *
     * @param array{
     *   @region?: string,
     * }|DescribeLimitsInput $input
     *
     * @throws LimitExceededException
     */
    public function describeLimits($input = []): DescribeLimitsOutput
    {
        $input = DescribeLimitsInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DescribeLimits', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'LimitExceededException' => LimitExceededException::class,
        ]]));

        return new DescribeLimitsOutput($response);
    }

    /**
     * Describes the specified Kinesis data stream.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_DescribeStream.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#describestream
     *
     * @param array{
     *   StreamName: string,
     *   Limit?: int,
     *   ExclusiveStartShardId?: string,
     *   @region?: string,
     * }|DescribeStreamInput $input
     *
     * @throws ResourceNotFoundException
     * @throws LimitExceededException
     */
    public function describeStream($input): DescribeStreamOutput
    {
        $input = DescribeStreamInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DescribeStream', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'LimitExceededException' => LimitExceededException::class,
        ]]));

        return new DescribeStreamOutput($response, $this, $input);
    }

    /**
     * To get the description of a registered consumer, provide the ARN of the consumer. Alternatively, you can provide the
     * ARN of the data stream and the name you gave the consumer when you registered it. You may also provide all three
     * parameters, as long as they don't conflict with each other. If you don't know the name or ARN of the consumer that
     * you want to describe, you can use the ListStreamConsumers operation to get a list of the descriptions of all the
     * consumers that are currently registered with a given data stream.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_DescribeStreamConsumer.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#describestreamconsumer
     *
     * @param array{
     *   StreamARN?: string,
     *   ConsumerName?: string,
     *   ConsumerARN?: string,
     *   @region?: string,
     * }|DescribeStreamConsumerInput $input
     *
     * @throws LimitExceededException
     * @throws ResourceNotFoundException
     * @throws InvalidArgumentException
     */
    public function describeStreamConsumer($input = []): DescribeStreamConsumerOutput
    {
        $input = DescribeStreamConsumerInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DescribeStreamConsumer', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'LimitExceededException' => LimitExceededException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidArgumentException' => InvalidArgumentException::class,
        ]]));

        return new DescribeStreamConsumerOutput($response);
    }

    /**
     * Provides a summarized description of the specified Kinesis data stream without the shard list.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_DescribeStreamSummary.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#describestreamsummary
     *
     * @param array{
     *   StreamName: string,
     *   @region?: string,
     * }|DescribeStreamSummaryInput $input
     *
     * @throws ResourceNotFoundException
     * @throws LimitExceededException
     */
    public function describeStreamSummary($input): DescribeStreamSummaryOutput
    {
        $input = DescribeStreamSummaryInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DescribeStreamSummary', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'LimitExceededException' => LimitExceededException::class,
        ]]));

        return new DescribeStreamSummaryOutput($response);
    }

    /**
     * Disables enhanced monitoring.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_DisableEnhancedMonitoring.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#disableenhancedmonitoring
     *
     * @param array{
     *   StreamName: string,
     *   ShardLevelMetrics: list<MetricsName::*>,
     *   @region?: string,
     * }|DisableEnhancedMonitoringInput $input
     *
     * @throws InvalidArgumentException
     * @throws LimitExceededException
     * @throws ResourceInUseException
     * @throws ResourceNotFoundException
     */
    public function disableEnhancedMonitoring($input): EnhancedMonitoringOutput
    {
        $input = DisableEnhancedMonitoringInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DisableEnhancedMonitoring', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidArgumentException' => InvalidArgumentException::class,
            'LimitExceededException' => LimitExceededException::class,
            'ResourceInUseException' => ResourceInUseException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
        ]]));

        return new EnhancedMonitoringOutput($response);
    }

    /**
     * Enables enhanced Kinesis data stream monitoring for shard-level metrics.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_EnableEnhancedMonitoring.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#enableenhancedmonitoring
     *
     * @param array{
     *   StreamName: string,
     *   ShardLevelMetrics: list<MetricsName::*>,
     *   @region?: string,
     * }|EnableEnhancedMonitoringInput $input
     *
     * @throws InvalidArgumentException
     * @throws LimitExceededException
     * @throws ResourceInUseException
     * @throws ResourceNotFoundException
     */
    public function enableEnhancedMonitoring($input): EnhancedMonitoringOutput
    {
        $input = EnableEnhancedMonitoringInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'EnableEnhancedMonitoring', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidArgumentException' => InvalidArgumentException::class,
            'LimitExceededException' => LimitExceededException::class,
            'ResourceInUseException' => ResourceInUseException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
        ]]));

        return new EnhancedMonitoringOutput($response);
    }

    /**
     * Gets data records from a Kinesis data stream's shard.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_GetRecords.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#getrecords
     *
     * @param array{
     *   ShardIterator: string,
     *   Limit?: int,
     *   @region?: string,
     * }|GetRecordsInput $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidArgumentException
     * @throws ProvisionedThroughputExceededException
     * @throws ExpiredIteratorException
     * @throws KMSDisabledException
     * @throws KMSInvalidStateException
     * @throws KMSAccessDeniedException
     * @throws KMSNotFoundException
     * @throws KMSOptInRequiredException
     * @throws KMSThrottlingException
     */
    public function getRecords($input): GetRecordsOutput
    {
        $input = GetRecordsInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetRecords', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidArgumentException' => InvalidArgumentException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
            'ExpiredIteratorException' => ExpiredIteratorException::class,
            'KMSDisabledException' => KMSDisabledException::class,
            'KMSInvalidStateException' => KMSInvalidStateException::class,
            'KMSAccessDeniedException' => KMSAccessDeniedException::class,
            'KMSNotFoundException' => KMSNotFoundException::class,
            'KMSOptInRequired' => KMSOptInRequiredException::class,
            'KMSThrottlingException' => KMSThrottlingException::class,
        ]]));

        return new GetRecordsOutput($response);
    }

    /**
     * Gets an Amazon Kinesis shard iterator. A shard iterator expires 5 minutes after it is returned to the requester.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_GetShardIterator.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#getsharditerator
     *
     * @param array{
     *   StreamName: string,
     *   ShardId: string,
     *   ShardIteratorType: ShardIteratorType::*,
     *   StartingSequenceNumber?: string,
     *   Timestamp?: \DateTimeImmutable|string,
     *   @region?: string,
     * }|GetShardIteratorInput $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidArgumentException
     * @throws ProvisionedThroughputExceededException
     */
    public function getShardIterator($input): GetShardIteratorOutput
    {
        $input = GetShardIteratorInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetShardIterator', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidArgumentException' => InvalidArgumentException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
        ]]));

        return new GetShardIteratorOutput($response);
    }

    /**
     * Increases the Kinesis data stream's retention period, which is the length of time data records are accessible after
     * they are added to the stream. The maximum value of a stream's retention period is 168 hours (7 days).
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_IncreaseStreamRetentionPeriod.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#increasestreamretentionperiod
     *
     * @param array{
     *   StreamName: string,
     *   RetentionPeriodHours: int,
     *   @region?: string,
     * }|IncreaseStreamRetentionPeriodInput $input
     *
     * @throws ResourceInUseException
     * @throws ResourceNotFoundException
     * @throws LimitExceededException
     * @throws InvalidArgumentException
     */
    public function increaseStreamRetentionPeriod($input): Result
    {
        $input = IncreaseStreamRetentionPeriodInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'IncreaseStreamRetentionPeriod', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceInUseException' => ResourceInUseException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'LimitExceededException' => LimitExceededException::class,
            'InvalidArgumentException' => InvalidArgumentException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Lists the shards in a stream and provides information about each shard. This operation has a limit of 100
     * transactions per second per data stream.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_ListShards.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#listshards
     *
     * @param array{
     *   StreamName?: string,
     *   NextToken?: string,
     *   ExclusiveStartShardId?: string,
     *   MaxResults?: int,
     *   StreamCreationTimestamp?: \DateTimeImmutable|string,
     *   @region?: string,
     * }|ListShardsInput $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidArgumentException
     * @throws LimitExceededException
     * @throws ExpiredNextTokenException
     * @throws ResourceInUseException
     */
    public function listShards($input = []): ListShardsOutput
    {
        $input = ListShardsInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListShards', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidArgumentException' => InvalidArgumentException::class,
            'LimitExceededException' => LimitExceededException::class,
            'ExpiredNextTokenException' => ExpiredNextTokenException::class,
            'ResourceInUseException' => ResourceInUseException::class,
        ]]));

        return new ListShardsOutput($response);
    }

    /**
     * Lists the consumers registered to receive data from a stream using enhanced fan-out, and provides information about
     * each consumer.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_ListStreamConsumers.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#liststreamconsumers
     *
     * @param array{
     *   StreamARN: string,
     *   NextToken?: string,
     *   MaxResults?: int,
     *   StreamCreationTimestamp?: \DateTimeImmutable|string,
     *   @region?: string,
     * }|ListStreamConsumersInput $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidArgumentException
     * @throws LimitExceededException
     * @throws ExpiredNextTokenException
     * @throws ResourceInUseException
     */
    public function listStreamConsumers($input): ListStreamConsumersOutput
    {
        $input = ListStreamConsumersInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListStreamConsumers', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidArgumentException' => InvalidArgumentException::class,
            'LimitExceededException' => LimitExceededException::class,
            'ExpiredNextTokenException' => ExpiredNextTokenException::class,
            'ResourceInUseException' => ResourceInUseException::class,
        ]]));

        return new ListStreamConsumersOutput($response, $this, $input);
    }

    /**
     * Lists your Kinesis data streams.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_ListStreams.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#liststreams
     *
     * @param array{
     *   Limit?: int,
     *   ExclusiveStartStreamName?: string,
     *   @region?: string,
     * }|ListStreamsInput $input
     *
     * @throws LimitExceededException
     */
    public function listStreams($input = []): ListStreamsOutput
    {
        $input = ListStreamsInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListStreams', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'LimitExceededException' => LimitExceededException::class,
        ]]));

        return new ListStreamsOutput($response, $this, $input);
    }

    /**
     * Lists the tags for the specified Kinesis data stream. This operation has a limit of five transactions per second per
     * account.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_ListTagsForStream.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#listtagsforstream
     *
     * @param array{
     *   StreamName: string,
     *   ExclusiveStartTagKey?: string,
     *   Limit?: int,
     *   @region?: string,
     * }|ListTagsForStreamInput $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidArgumentException
     * @throws LimitExceededException
     */
    public function listTagsForStream($input): ListTagsForStreamOutput
    {
        $input = ListTagsForStreamInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListTagsForStream', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidArgumentException' => InvalidArgumentException::class,
            'LimitExceededException' => LimitExceededException::class,
        ]]));

        return new ListTagsForStreamOutput($response);
    }

    /**
     * Merges two adjacent shards in a Kinesis data stream and combines them into a single shard to reduce the stream's
     * capacity to ingest and transport data. Two shards are considered adjacent if the union of the hash key ranges for the
     * two shards form a contiguous set with no gaps. For example, if you have two shards, one with a hash key range of
     * 276...381 and the other with a hash key range of 382...454, then you could merge these two shards into a single shard
     * that would have a hash key range of 276...454. After the merge, the single child shard receives data for all hash key
     * values covered by the two parent shards.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_MergeShards.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#mergeshards
     *
     * @param array{
     *   StreamName: string,
     *   ShardToMerge: string,
     *   AdjacentShardToMerge: string,
     *   @region?: string,
     * }|MergeShardsInput $input
     *
     * @throws ResourceNotFoundException
     * @throws ResourceInUseException
     * @throws InvalidArgumentException
     * @throws LimitExceededException
     */
    public function mergeShards($input): Result
    {
        $input = MergeShardsInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'MergeShards', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ResourceInUseException' => ResourceInUseException::class,
            'InvalidArgumentException' => InvalidArgumentException::class,
            'LimitExceededException' => LimitExceededException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Writes a single data record into an Amazon Kinesis data stream. Call `PutRecord` to send data into the stream for
     * real-time ingestion and subsequent processing, one record at a time. Each shard can support writes up to 1,000
     * records per second, up to a maximum data write total of 1 MB per second.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_PutRecord.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#putrecord
     *
     * @param array{
     *   StreamName: string,
     *   Data: string,
     *   PartitionKey: string,
     *   ExplicitHashKey?: string,
     *   SequenceNumberForOrdering?: string,
     *   @region?: string,
     * }|PutRecordInput $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidArgumentException
     * @throws ProvisionedThroughputExceededException
     * @throws KMSDisabledException
     * @throws KMSInvalidStateException
     * @throws KMSAccessDeniedException
     * @throws KMSNotFoundException
     * @throws KMSOptInRequiredException
     * @throws KMSThrottlingException
     */
    public function putRecord($input): PutRecordOutput
    {
        $input = PutRecordInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutRecord', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidArgumentException' => InvalidArgumentException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
            'KMSDisabledException' => KMSDisabledException::class,
            'KMSInvalidStateException' => KMSInvalidStateException::class,
            'KMSAccessDeniedException' => KMSAccessDeniedException::class,
            'KMSNotFoundException' => KMSNotFoundException::class,
            'KMSOptInRequired' => KMSOptInRequiredException::class,
            'KMSThrottlingException' => KMSThrottlingException::class,
        ]]));

        return new PutRecordOutput($response);
    }

    /**
     * Writes multiple data records into a Kinesis data stream in a single call (also referred to as a `PutRecords`
     * request). Use this operation to send data into the stream for data ingestion and processing.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_PutRecords.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#putrecords
     *
     * @param array{
     *   Records: PutRecordsRequestEntry[],
     *   StreamName: string,
     *   @region?: string,
     * }|PutRecordsInput $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidArgumentException
     * @throws ProvisionedThroughputExceededException
     * @throws KMSDisabledException
     * @throws KMSInvalidStateException
     * @throws KMSAccessDeniedException
     * @throws KMSNotFoundException
     * @throws KMSOptInRequiredException
     * @throws KMSThrottlingException
     */
    public function putRecords($input): PutRecordsOutput
    {
        $input = PutRecordsInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutRecords', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidArgumentException' => InvalidArgumentException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
            'KMSDisabledException' => KMSDisabledException::class,
            'KMSInvalidStateException' => KMSInvalidStateException::class,
            'KMSAccessDeniedException' => KMSAccessDeniedException::class,
            'KMSNotFoundException' => KMSNotFoundException::class,
            'KMSOptInRequired' => KMSOptInRequiredException::class,
            'KMSThrottlingException' => KMSThrottlingException::class,
        ]]));

        return new PutRecordsOutput($response);
    }

    /**
     * Registers a consumer with a Kinesis data stream. When you use this operation, the consumer you register can read data
     * from the stream at a rate of up to 2 MiB per second. This rate is unaffected by the total number of consumers that
     * read from the same stream.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_RegisterStreamConsumer.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#registerstreamconsumer
     *
     * @param array{
     *   StreamARN: string,
     *   ConsumerName: string,
     *   @region?: string,
     * }|RegisterStreamConsumerInput $input
     *
     * @throws InvalidArgumentException
     * @throws LimitExceededException
     * @throws ResourceInUseException
     * @throws ResourceNotFoundException
     */
    public function registerStreamConsumer($input): RegisterStreamConsumerOutput
    {
        $input = RegisterStreamConsumerInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'RegisterStreamConsumer', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidArgumentException' => InvalidArgumentException::class,
            'LimitExceededException' => LimitExceededException::class,
            'ResourceInUseException' => ResourceInUseException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
        ]]));

        return new RegisterStreamConsumerOutput($response);
    }

    /**
     * Removes tags from the specified Kinesis data stream. Removed tags are deleted and cannot be recovered after this
     * operation successfully completes.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_RemoveTagsFromStream.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#removetagsfromstream
     *
     * @param array{
     *   StreamName: string,
     *   TagKeys: string[],
     *   @region?: string,
     * }|RemoveTagsFromStreamInput $input
     *
     * @throws ResourceNotFoundException
     * @throws ResourceInUseException
     * @throws InvalidArgumentException
     * @throws LimitExceededException
     */
    public function removeTagsFromStream($input): Result
    {
        $input = RemoveTagsFromStreamInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'RemoveTagsFromStream', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ResourceInUseException' => ResourceInUseException::class,
            'InvalidArgumentException' => InvalidArgumentException::class,
            'LimitExceededException' => LimitExceededException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Splits a shard into two new shards in the Kinesis data stream, to increase the stream's capacity to ingest and
     * transport data. `SplitShard` is called when there is a need to increase the overall capacity of a stream because of
     * an expected increase in the volume of data records being ingested.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_SplitShard.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#splitshard
     *
     * @param array{
     *   StreamName: string,
     *   ShardToSplit: string,
     *   NewStartingHashKey: string,
     *   @region?: string,
     * }|SplitShardInput $input
     *
     * @throws ResourceNotFoundException
     * @throws ResourceInUseException
     * @throws InvalidArgumentException
     * @throws LimitExceededException
     */
    public function splitShard($input): Result
    {
        $input = SplitShardInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'SplitShard', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ResourceInUseException' => ResourceInUseException::class,
            'InvalidArgumentException' => InvalidArgumentException::class,
            'LimitExceededException' => LimitExceededException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Enables or updates server-side encryption using an AWS KMS key for a specified stream.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_StartStreamEncryption.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#startstreamencryption
     *
     * @param array{
     *   StreamName: string,
     *   EncryptionType: EncryptionType::*,
     *   KeyId: string,
     *   @region?: string,
     * }|StartStreamEncryptionInput $input
     *
     * @throws InvalidArgumentException
     * @throws LimitExceededException
     * @throws ResourceInUseException
     * @throws ResourceNotFoundException
     * @throws KMSDisabledException
     * @throws KMSInvalidStateException
     * @throws KMSAccessDeniedException
     * @throws KMSNotFoundException
     * @throws KMSOptInRequiredException
     * @throws KMSThrottlingException
     */
    public function startStreamEncryption($input): Result
    {
        $input = StartStreamEncryptionInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'StartStreamEncryption', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidArgumentException' => InvalidArgumentException::class,
            'LimitExceededException' => LimitExceededException::class,
            'ResourceInUseException' => ResourceInUseException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'KMSDisabledException' => KMSDisabledException::class,
            'KMSInvalidStateException' => KMSInvalidStateException::class,
            'KMSAccessDeniedException' => KMSAccessDeniedException::class,
            'KMSNotFoundException' => KMSNotFoundException::class,
            'KMSOptInRequired' => KMSOptInRequiredException::class,
            'KMSThrottlingException' => KMSThrottlingException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Disables server-side encryption for a specified stream.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_StopStreamEncryption.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#stopstreamencryption
     *
     * @param array{
     *   StreamName: string,
     *   EncryptionType: EncryptionType::*,
     *   KeyId: string,
     *   @region?: string,
     * }|StopStreamEncryptionInput $input
     *
     * @throws InvalidArgumentException
     * @throws LimitExceededException
     * @throws ResourceInUseException
     * @throws ResourceNotFoundException
     */
    public function stopStreamEncryption($input): Result
    {
        $input = StopStreamEncryptionInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'StopStreamEncryption', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidArgumentException' => InvalidArgumentException::class,
            'LimitExceededException' => LimitExceededException::class,
            'ResourceInUseException' => ResourceInUseException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
        ]]));

        return new Result($response);
    }

    /**
     * @see describeStream
     *
     * @param array{
     *   StreamName: string,
     *   Limit?: int,
     *   ExclusiveStartShardId?: string,
     *   @region?: string,
     * }|DescribeStreamInput $input
     */
    public function streamExists($input): StreamExistsWaiter
    {
        $input = DescribeStreamInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DescribeStream', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'LimitExceededException' => LimitExceededException::class,
        ]]));

        return new StreamExistsWaiter($response, $this, $input);
    }

    /**
     * @see describeStream
     *
     * @param array{
     *   StreamName: string,
     *   Limit?: int,
     *   ExclusiveStartShardId?: string,
     *   @region?: string,
     * }|DescribeStreamInput $input
     */
    public function streamNotExists($input): StreamNotExistsWaiter
    {
        $input = DescribeStreamInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DescribeStream', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'LimitExceededException' => LimitExceededException::class,
        ]]));

        return new StreamNotExistsWaiter($response, $this, $input);
    }

    /**
     * Updates the shard count of the specified stream to the specified number of shards.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_UpdateShardCount.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#updateshardcount
     *
     * @param array{
     *   StreamName: string,
     *   TargetShardCount: int,
     *   ScalingType: ScalingType::*,
     *   @region?: string,
     * }|UpdateShardCountInput $input
     *
     * @throws InvalidArgumentException
     * @throws LimitExceededException
     * @throws ResourceInUseException
     * @throws ResourceNotFoundException
     */
    public function updateShardCount($input): UpdateShardCountOutput
    {
        $input = UpdateShardCountInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'UpdateShardCount', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidArgumentException' => InvalidArgumentException::class,
            'LimitExceededException' => LimitExceededException::class,
            'ResourceInUseException' => ResourceInUseException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
        ]]));

        return new UpdateShardCountOutput($response);
    }

    protected function getAwsErrorFactory(): AwsErrorFactoryInterface
    {
        return new JsonRpcAwsErrorFactory();
    }

    protected function getEndpointMetadata(?string $region): array
    {
        if (null === $region) {
            $region = Configuration::DEFAULT_REGION;
        }

        switch ($region) {
            case 'cn-north-1':
            case 'cn-northwest-1':
                return [
                    'endpoint' => "https://kinesis.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'kinesis',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
                return [
                    'endpoint' => "https://kinesis.$region.c2s.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'kinesis',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
                return [
                    'endpoint' => "https://kinesis.$region.sc2s.sgov.gov",
                    'signRegion' => $region,
                    'signService' => 'kinesis',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-1':
                return [
                    'endpoint' => 'https://kinesis-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'kinesis',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-2':
                return [
                    'endpoint' => 'https://kinesis-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'kinesis',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-1':
                return [
                    'endpoint' => 'https://kinesis-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'kinesis',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-2':
                return [
                    'endpoint' => 'https://kinesis-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'kinesis',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-east-1':
                return [
                    'endpoint' => 'https://kinesis.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'kinesis',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-west-1':
                return [
                    'endpoint' => 'https://kinesis.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'kinesis',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://kinesis.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'kinesis',
            'signVersions' => ['v4'],
        ];
    }
}
