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
use AsyncAws\Kinesis\Exception\AccessDeniedException;
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
use AsyncAws\Kinesis\Exception\ValidationException;
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
use AsyncAws\Kinesis\ValueObject\ShardFilter;
use AsyncAws\Kinesis\ValueObject\StreamModeDetails;

class KinesisClient extends AbstractApi
{
    /**
     * Adds or updates tags for the specified Kinesis data stream. You can assign up to 50 tags to a data stream.
     *
     * > When invoking this API, you must use either the `StreamARN` or the `StreamName` parameter, or both. It is
     * > recommended that you use the `StreamARN` input parameter when you invoke this API.
     *
     * If tags have already been assigned to the stream, `AddTagsToStream` overwrites any existing tags that correspond to
     * the specified tag keys.
     *
     * AddTagsToStream has a limit of five transactions per second per account.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_AddTagsToStream.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#addtagstostream
     *
     * @param array{
     *   StreamName?: null|string,
     *   Tags: array<string, string>,
     *   StreamARN?: null|string,
     *   '@region'?: string|null,
     * }|AddTagsToStreamInput $input
     *
     * @throws ResourceNotFoundException
     * @throws ResourceInUseException
     * @throws InvalidArgumentException
     * @throws LimitExceededException
     * @throws AccessDeniedException
     */
    public function addTagsToStream($input): Result
    {
        $input = AddTagsToStreamInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AddTagsToStream', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ResourceInUseException' => ResourceInUseException::class,
            'InvalidArgumentException' => InvalidArgumentException::class,
            'LimitExceededException' => LimitExceededException::class,
            'AccessDeniedException' => AccessDeniedException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Creates a Kinesis data stream. A stream captures and transports data records that are continuously emitted from
     * different data sources or *producers*. Scale-out within a stream is explicitly supported by means of shards, which
     * are uniquely identified groups of data records in a stream.
     *
     * You can create your data stream using either on-demand or provisioned capacity mode. Data streams with an on-demand
     * mode require no capacity planning and automatically scale to handle gigabytes of write and read throughput per
     * minute. With the on-demand mode, Kinesis Data Streams automatically manages the shards in order to provide the
     * necessary throughput. For the data streams with a provisioned mode, you must specify the number of shards for the
     * data stream. Each shard can support reads up to five transactions per second, up to a maximum data read total of 2
     * MiB per second. Each shard can support writes up to 1,000 records per second, up to a maximum data write total of 1
     * MiB per second. If the amount of data input increases or decreases, you can add or remove shards.
     *
     * The stream name identifies the stream. The name is scoped to the Amazon Web Services account used by the application.
     * It is also scoped by Amazon Web Services Region. That is, two streams in two different accounts can have the same
     * name, and two streams in the same account, but in two different Regions, can have the same name.
     *
     * `CreateStream` is an asynchronous operation. Upon receiving a `CreateStream` request, Kinesis Data Streams
     * immediately returns and sets the stream status to `CREATING`. After the stream is created, Kinesis Data Streams sets
     * the stream status to `ACTIVE`. You should perform read and write operations only on an `ACTIVE` stream.
     *
     * You receive a `LimitExceededException` when making a `CreateStream` request when you try to do one of the following:
     *
     * - Have more than five streams in the `CREATING` state at any point in time.
     * - Create more shards than are authorized for your account.
     *
     * For the default shard limit for an Amazon Web Services account, see Amazon Kinesis Data Streams Limits [^1] in the
     * *Amazon Kinesis Data Streams Developer Guide*. To increase this limit, contact Amazon Web Services Support [^2].
     *
     * You can use DescribeStreamSummary to check the stream status, which is returned in `StreamStatus`.
     *
     * CreateStream has a limit of five transactions per second per account.
     *
     * You can add tags to the stream when making a `CreateStream` request by setting the `Tags` parameter. If you pass
     * `Tags` parameter, in addition to having `kinesis:createStream` permission, you must also have
     * `kinesis:addTagsToStream` permission for the stream that will be created. Tags will take effect from the `CREATING`
     * status of the stream.
     *
     * [^1]: https://docs.aws.amazon.com/kinesis/latest/dev/service-sizes-and-limits.html
     * [^2]: https://docs.aws.amazon.com/general/latest/gr/aws_service_limits.html
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_CreateStream.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#createstream
     *
     * @param array{
     *   StreamName: string,
     *   ShardCount?: null|int,
     *   StreamModeDetails?: null|StreamModeDetails|array,
     *   Tags?: null|array<string, string>,
     *   '@region'?: string|null,
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
     * > When invoking this API, you must use either the `StreamARN` or the `StreamName` parameter, or both. It is
     * > recommended that you use the `StreamARN` input parameter when you invoke this API.
     *
     * This operation may result in lost data. For example, if the stream's retention period is 48 hours and is decreased to
     * 24 hours, any data already in the stream that is older than 24 hours is inaccessible.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_DecreaseStreamRetentionPeriod.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#decreasestreamretentionperiod
     *
     * @param array{
     *   StreamName?: null|string,
     *   RetentionPeriodHours: int,
     *   StreamARN?: null|string,
     *   '@region'?: string|null,
     * }|DecreaseStreamRetentionPeriodInput $input
     *
     * @throws ResourceInUseException
     * @throws ResourceNotFoundException
     * @throws LimitExceededException
     * @throws InvalidArgumentException
     * @throws AccessDeniedException
     */
    public function decreaseStreamRetentionPeriod($input): Result
    {
        $input = DecreaseStreamRetentionPeriodInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DecreaseStreamRetentionPeriod', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceInUseException' => ResourceInUseException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'LimitExceededException' => LimitExceededException::class,
            'InvalidArgumentException' => InvalidArgumentException::class,
            'AccessDeniedException' => AccessDeniedException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Deletes a Kinesis data stream and all its shards and data. You must shut down any applications that are operating on
     * the stream before you delete the stream. If an application attempts to operate on a deleted stream, it receives the
     * exception `ResourceNotFoundException`.
     *
     * > When invoking this API, you must use either the `StreamARN` or the `StreamName` parameter, or both. It is
     * > recommended that you use the `StreamARN` input parameter when you invoke this API.
     *
     * If the stream is in the `ACTIVE` state, you can delete it. After a `DeleteStream` request, the specified stream is in
     * the `DELETING` state until Kinesis Data Streams completes the deletion.
     *
     * **Note:** Kinesis Data Streams might continue to accept data read and write operations, such as PutRecord,
     * PutRecords, and GetRecords, on a stream in the `DELETING` state until the stream deletion is complete.
     *
     * When you delete a stream, any shards in that stream are also deleted, and any tags are dissociated from the stream.
     *
     * You can use the DescribeStreamSummary operation to check the state of the stream, which is returned in
     * `StreamStatus`.
     *
     * DeleteStream has a limit of five transactions per second per account.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_DeleteStream.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#deletestream
     *
     * @param array{
     *   StreamName?: null|string,
     *   EnforceConsumerDeletion?: null|bool,
     *   StreamARN?: null|string,
     *   '@region'?: string|null,
     * }|DeleteStreamInput $input
     *
     * @throws ResourceNotFoundException
     * @throws LimitExceededException
     * @throws ResourceInUseException
     * @throws InvalidArgumentException
     * @throws AccessDeniedException
     */
    public function deleteStream($input = []): Result
    {
        $input = DeleteStreamInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteStream', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'LimitExceededException' => LimitExceededException::class,
            'ResourceInUseException' => ResourceInUseException::class,
            'InvalidArgumentException' => InvalidArgumentException::class,
            'AccessDeniedException' => AccessDeniedException::class,
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
     * This operation has a limit of five transactions per second per stream.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_DeregisterStreamConsumer.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#deregisterstreamconsumer
     *
     * @param array{
     *   StreamARN?: null|string,
     *   ConsumerName?: null|string,
     *   ConsumerARN?: null|string,
     *   '@region'?: string|null,
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
     * If you update your account limits, the old limits might be returned for a few minutes.
     *
     * This operation has a limit of one transaction per second per account.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_DescribeLimits.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#describelimits
     *
     * @param array{
     *   '@region'?: string|null,
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
     * > This API has been revised. It's highly recommended that you use the DescribeStreamSummary API to get a summarized
     * > description of the specified Kinesis data stream and the ListShards API to list the shards in a specified data
     * > stream and obtain information about each shard.
     *
     * > When invoking this API, you must use either the `StreamARN` or the `StreamName` parameter, or both. It is
     * > recommended that you use the `StreamARN` input parameter when you invoke this API.
     *
     * The information returned includes the stream name, Amazon Resource Name (ARN), creation time, enhanced metric
     * configuration, and shard map. The shard map is an array of shard objects. For each shard object, there is the hash
     * key and sequence number ranges that the shard spans, and the IDs of any earlier shards that played in a role in
     * creating the shard. Every record ingested in the stream is identified by a sequence number, which is assigned when
     * the record is put into the stream.
     *
     * You can limit the number of shards returned by each call. For more information, see Retrieving Shards from a Stream
     * [^1] in the *Amazon Kinesis Data Streams Developer Guide*.
     *
     * There are no guarantees about the chronological order shards returned. To process shards in chronological order, use
     * the ID of the parent shard to track the lineage to the oldest shard.
     *
     * This operation has a limit of 10 transactions per second per account.
     *
     * [^1]: https://docs.aws.amazon.com/kinesis/latest/dev/kinesis-using-sdk-java-retrieve-shards.html
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_DescribeStream.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#describestream
     *
     * @param array{
     *   StreamName?: null|string,
     *   Limit?: null|int,
     *   ExclusiveStartShardId?: null|string,
     *   StreamARN?: null|string,
     *   '@region'?: string|null,
     * }|DescribeStreamInput $input
     *
     * @throws ResourceNotFoundException
     * @throws LimitExceededException
     * @throws InvalidArgumentException
     * @throws AccessDeniedException
     */
    public function describeStream($input = []): DescribeStreamOutput
    {
        $input = DescribeStreamInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DescribeStream', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'LimitExceededException' => LimitExceededException::class,
            'InvalidArgumentException' => InvalidArgumentException::class,
            'AccessDeniedException' => AccessDeniedException::class,
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
     * This operation has a limit of 20 transactions per second per stream.
     *
     * > When making a cross-account call with `DescribeStreamConsumer`, make sure to provide the ARN of the consumer.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_DescribeStreamConsumer.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#describestreamconsumer
     *
     * @param array{
     *   StreamARN?: null|string,
     *   ConsumerName?: null|string,
     *   ConsumerARN?: null|string,
     *   '@region'?: string|null,
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
     * > When invoking this API, you must use either the `StreamARN` or the `StreamName` parameter, or both. It is
     * > recommended that you use the `StreamARN` input parameter when you invoke this API.
     *
     * The information returned includes the stream name, Amazon Resource Name (ARN), status, record retention period,
     * approximate creation time, monitoring, encryption details, and open shard count.
     *
     * DescribeStreamSummary has a limit of 20 transactions per second per account.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_DescribeStreamSummary.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#describestreamsummary
     *
     * @param array{
     *   StreamName?: null|string,
     *   StreamARN?: null|string,
     *   '@region'?: string|null,
     * }|DescribeStreamSummaryInput $input
     *
     * @throws ResourceNotFoundException
     * @throws LimitExceededException
     * @throws InvalidArgumentException
     * @throws AccessDeniedException
     */
    public function describeStreamSummary($input = []): DescribeStreamSummaryOutput
    {
        $input = DescribeStreamSummaryInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DescribeStreamSummary', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'LimitExceededException' => LimitExceededException::class,
            'InvalidArgumentException' => InvalidArgumentException::class,
            'AccessDeniedException' => AccessDeniedException::class,
        ]]));

        return new DescribeStreamSummaryOutput($response);
    }

    /**
     * Disables enhanced monitoring.
     *
     * > When invoking this API, you must use either the `StreamARN` or the `StreamName` parameter, or both. It is
     * > recommended that you use the `StreamARN` input parameter when you invoke this API.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_DisableEnhancedMonitoring.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#disableenhancedmonitoring
     *
     * @param array{
     *   StreamName?: null|string,
     *   ShardLevelMetrics: array<MetricsName::*>,
     *   StreamARN?: null|string,
     *   '@region'?: string|null,
     * }|DisableEnhancedMonitoringInput $input
     *
     * @throws InvalidArgumentException
     * @throws LimitExceededException
     * @throws ResourceInUseException
     * @throws ResourceNotFoundException
     * @throws AccessDeniedException
     */
    public function disableEnhancedMonitoring($input): EnhancedMonitoringOutput
    {
        $input = DisableEnhancedMonitoringInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DisableEnhancedMonitoring', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidArgumentException' => InvalidArgumentException::class,
            'LimitExceededException' => LimitExceededException::class,
            'ResourceInUseException' => ResourceInUseException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'AccessDeniedException' => AccessDeniedException::class,
        ]]));

        return new EnhancedMonitoringOutput($response);
    }

    /**
     * Enables enhanced Kinesis data stream monitoring for shard-level metrics.
     *
     * > When invoking this API, you must use either the `StreamARN` or the `StreamName` parameter, or both. It is
     * > recommended that you use the `StreamARN` input parameter when you invoke this API.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_EnableEnhancedMonitoring.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#enableenhancedmonitoring
     *
     * @param array{
     *   StreamName?: null|string,
     *   ShardLevelMetrics: array<MetricsName::*>,
     *   StreamARN?: null|string,
     *   '@region'?: string|null,
     * }|EnableEnhancedMonitoringInput $input
     *
     * @throws InvalidArgumentException
     * @throws LimitExceededException
     * @throws ResourceInUseException
     * @throws ResourceNotFoundException
     * @throws AccessDeniedException
     */
    public function enableEnhancedMonitoring($input): EnhancedMonitoringOutput
    {
        $input = EnableEnhancedMonitoringInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'EnableEnhancedMonitoring', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidArgumentException' => InvalidArgumentException::class,
            'LimitExceededException' => LimitExceededException::class,
            'ResourceInUseException' => ResourceInUseException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'AccessDeniedException' => AccessDeniedException::class,
        ]]));

        return new EnhancedMonitoringOutput($response);
    }

    /**
     * Gets data records from a Kinesis data stream's shard.
     *
     * > When invoking this API, you must use either the `StreamARN` or the `StreamName` parameter, or both. It is
     * > recommended that you use the `StreamARN` input parameter when you invoke this API.
     *
     * Specify a shard iterator using the `ShardIterator` parameter. The shard iterator specifies the position in the shard
     * from which you want to start reading data records sequentially. If there are no records available in the portion of
     * the shard that the iterator points to, GetRecords returns an empty list. It might take multiple calls to get to a
     * portion of the shard that contains records.
     *
     * You can scale by provisioning multiple shards per stream while considering service limits (for more information, see
     * Amazon Kinesis Data Streams Limits [^1] in the *Amazon Kinesis Data Streams Developer Guide*). Your application
     * should have one thread per shard, each reading continuously from its stream. To read from a stream continually, call
     * GetRecords in a loop. Use GetShardIterator to get the shard iterator to specify in the first GetRecords call.
     * GetRecords returns a new shard iterator in `NextShardIterator`. Specify the shard iterator returned in
     * `NextShardIterator` in subsequent calls to GetRecords. If the shard has been closed, the shard iterator can't return
     * more data and GetRecords returns `null` in `NextShardIterator`. You can terminate the loop when the shard is closed,
     * or when the shard iterator reaches the record with the sequence number or other attribute that marks it as the last
     * record to process.
     *
     * Each data record can be up to 1 MiB in size, and each shard can read up to 2 MiB per second. You can ensure that your
     * calls don't exceed the maximum supported size or throughput by using the `Limit` parameter to specify the maximum
     * number of records that GetRecords can return. Consider your average record size when determining this limit. The
     * maximum number of records that can be returned per call is 10,000.
     *
     * The size of the data returned by GetRecords varies depending on the utilization of the shard. It is recommended that
     * consumer applications retrieve records via the `GetRecords` command using the 5 TPS limit to remain caught up.
     * Retrieving records less frequently can lead to consumer applications falling behind. The maximum size of data that
     * GetRecords can return is 10 MiB. If a call returns this amount of data, subsequent calls made within the next 5
     * seconds throw `ProvisionedThroughputExceededException`. If there is insufficient provisioned throughput on the
     * stream, subsequent calls made within the next 1 second throw `ProvisionedThroughputExceededException`. GetRecords
     * doesn't return any data when it throws an exception. For this reason, we recommend that you wait 1 second between
     * calls to GetRecords. However, it's possible that the application will get exceptions for longer than 1 second.
     *
     * To detect whether the application is falling behind in processing, you can use the `MillisBehindLatest` response
     * attribute. You can also monitor the stream using CloudWatch metrics and other mechanisms (see Monitoring [^2] in the
     * *Amazon Kinesis Data Streams Developer Guide*).
     *
     * Each Amazon Kinesis record includes a value, `ApproximateArrivalTimestamp`, that is set when a stream successfully
     * receives and stores a record. This is commonly referred to as a server-side time stamp, whereas a client-side time
     * stamp is set when a data producer creates or sends the record to a stream (a data producer is any data source putting
     * data records into a stream, for example with PutRecords). The time stamp has millisecond precision. There are no
     * guarantees about the time stamp accuracy, or that the time stamp is always increasing. For example, records in a
     * shard or across a stream might have time stamps that are out of order.
     *
     * This operation has a limit of five transactions per second per shard.
     *
     * [^1]: https://docs.aws.amazon.com/kinesis/latest/dev/service-sizes-and-limits.html
     * [^2]: https://docs.aws.amazon.com/kinesis/latest/dev/monitoring.html
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_GetRecords.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#getrecords
     *
     * @param array{
     *   ShardIterator: string,
     *   Limit?: null|int,
     *   StreamARN?: null|string,
     *   '@region'?: string|null,
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
     * @throws AccessDeniedException
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
            'AccessDeniedException' => AccessDeniedException::class,
        ]]));

        return new GetRecordsOutput($response);
    }

    /**
     * Gets an Amazon Kinesis shard iterator. A shard iterator expires 5 minutes after it is returned to the requester.
     *
     * > When invoking this API, you must use either the `StreamARN` or the `StreamName` parameter, or both. It is
     * > recommended that you use the `StreamARN` input parameter when you invoke this API.
     *
     * A shard iterator specifies the shard position from which to start reading data records sequentially. The position is
     * specified using the sequence number of a data record in a shard. A sequence number is the identifier associated with
     * every record ingested in the stream, and is assigned when a record is put into the stream. Each stream has one or
     * more shards.
     *
     * You must specify the shard iterator type. For example, you can set the `ShardIteratorType` parameter to read exactly
     * from the position denoted by a specific sequence number by using the `AT_SEQUENCE_NUMBER` shard iterator type.
     * Alternatively, the parameter can read right after the sequence number by using the `AFTER_SEQUENCE_NUMBER` shard
     * iterator type, using sequence numbers returned by earlier calls to PutRecord, PutRecords, GetRecords, or
     * DescribeStream. In the request, you can specify the shard iterator type `AT_TIMESTAMP` to read records from an
     * arbitrary point in time, `TRIM_HORIZON` to cause `ShardIterator` to point to the last untrimmed record in the shard
     * in the system (the oldest data record in the shard), or `LATEST` so that you always read the most recent data in the
     * shard.
     *
     * When you read repeatedly from a stream, use a GetShardIterator request to get the first shard iterator for use in
     * your first GetRecords request and for subsequent reads use the shard iterator returned by the GetRecords request in
     * `NextShardIterator`. A new shard iterator is returned by every GetRecords request in `NextShardIterator`, which you
     * use in the `ShardIterator` parameter of the next GetRecords request.
     *
     * If a GetShardIterator request is made too often, you receive a `ProvisionedThroughputExceededException`. For more
     * information about throughput limits, see GetRecords, and Streams Limits [^1] in the *Amazon Kinesis Data Streams
     * Developer Guide*.
     *
     * If the shard is closed, GetShardIterator returns a valid iterator for the last sequence number of the shard. A shard
     * can be closed as a result of using SplitShard or MergeShards.
     *
     * GetShardIterator has a limit of five transactions per second per account per open shard.
     *
     * [^1]: https://docs.aws.amazon.com/kinesis/latest/dev/service-sizes-and-limits.html
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_GetShardIterator.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#getsharditerator
     *
     * @param array{
     *   StreamName?: null|string,
     *   ShardId: string,
     *   ShardIteratorType: ShardIteratorType::*,
     *   StartingSequenceNumber?: null|string,
     *   Timestamp?: null|\DateTimeImmutable|string,
     *   StreamARN?: null|string,
     *   '@region'?: string|null,
     * }|GetShardIteratorInput $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidArgumentException
     * @throws ProvisionedThroughputExceededException
     * @throws AccessDeniedException
     */
    public function getShardIterator($input): GetShardIteratorOutput
    {
        $input = GetShardIteratorInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetShardIterator', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidArgumentException' => InvalidArgumentException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
            'AccessDeniedException' => AccessDeniedException::class,
        ]]));

        return new GetShardIteratorOutput($response);
    }

    /**
     * Increases the Kinesis data stream's retention period, which is the length of time data records are accessible after
     * they are added to the stream. The maximum value of a stream's retention period is 8760 hours (365 days).
     *
     * > When invoking this API, you must use either the `StreamARN` or the `StreamName` parameter, or both. It is
     * > recommended that you use the `StreamARN` input parameter when you invoke this API.
     *
     * If you choose a longer stream retention period, this operation increases the time period during which records that
     * have not yet expired are accessible. However, it does not make previous, expired data (older than the stream's
     * previous retention period) accessible after the operation has been called. For example, if a stream's retention
     * period is set to 24 hours and is increased to 168 hours, any data that is older than 24 hours remains inaccessible to
     * consumer applications.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_IncreaseStreamRetentionPeriod.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#increasestreamretentionperiod
     *
     * @param array{
     *   StreamName?: null|string,
     *   RetentionPeriodHours: int,
     *   StreamARN?: null|string,
     *   '@region'?: string|null,
     * }|IncreaseStreamRetentionPeriodInput $input
     *
     * @throws ResourceInUseException
     * @throws ResourceNotFoundException
     * @throws LimitExceededException
     * @throws InvalidArgumentException
     * @throws AccessDeniedException
     */
    public function increaseStreamRetentionPeriod($input): Result
    {
        $input = IncreaseStreamRetentionPeriodInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'IncreaseStreamRetentionPeriod', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceInUseException' => ResourceInUseException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'LimitExceededException' => LimitExceededException::class,
            'InvalidArgumentException' => InvalidArgumentException::class,
            'AccessDeniedException' => AccessDeniedException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Lists the shards in a stream and provides information about each shard. This operation has a limit of 1000
     * transactions per second per data stream.
     *
     * > When invoking this API, you must use either the `StreamARN` or the `StreamName` parameter, or both. It is
     * > recommended that you use the `StreamARN` input parameter when you invoke this API.
     *
     * This action does not list expired shards. For information about expired shards, see Data Routing, Data Persistence,
     * and Shard State after a Reshard [^1].
     *
     * ! This API is a new operation that is used by the Amazon Kinesis Client Library (KCL). If you have a fine-grained IAM
     * ! policy that only allows specific operations, you must update your policy to allow calls to this API. For more
     * ! information, see Controlling Access to Amazon Kinesis Data Streams Resources Using IAM [^2].
     *
     * [^1]: https://docs.aws.amazon.com/streams/latest/dev/kinesis-using-sdk-java-after-resharding.html#kinesis-using-sdk-java-resharding-data-routing
     * [^2]: https://docs.aws.amazon.com/streams/latest/dev/controlling-access.html
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_ListShards.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#listshards
     *
     * @param array{
     *   StreamName?: null|string,
     *   NextToken?: null|string,
     *   ExclusiveStartShardId?: null|string,
     *   MaxResults?: null|int,
     *   StreamCreationTimestamp?: null|\DateTimeImmutable|string,
     *   ShardFilter?: null|ShardFilter|array,
     *   StreamARN?: null|string,
     *   '@region'?: string|null,
     * }|ListShardsInput $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidArgumentException
     * @throws LimitExceededException
     * @throws ExpiredNextTokenException
     * @throws ResourceInUseException
     * @throws AccessDeniedException
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
            'AccessDeniedException' => AccessDeniedException::class,
        ]]));

        return new ListShardsOutput($response);
    }

    /**
     * Lists the consumers registered to receive data from a stream using enhanced fan-out, and provides information about
     * each consumer.
     *
     * This operation has a limit of 5 transactions per second per stream.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_ListStreamConsumers.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#liststreamconsumers
     *
     * @param array{
     *   StreamARN: string,
     *   NextToken?: null|string,
     *   MaxResults?: null|int,
     *   StreamCreationTimestamp?: null|\DateTimeImmutable|string,
     *   '@region'?: string|null,
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
     * The number of streams may be too large to return from a single call to `ListStreams`. You can limit the number of
     * returned streams using the `Limit` parameter. If you do not specify a value for the `Limit` parameter, Kinesis Data
     * Streams uses the default limit, which is currently 100.
     *
     * You can detect if there are more streams available to list by using the `HasMoreStreams` flag from the returned
     * output. If there are more streams available, you can request more streams by using the name of the last stream
     * returned by the `ListStreams` request in the `ExclusiveStartStreamName` parameter in a subsequent request to
     * `ListStreams`. The group of stream names returned by the subsequent request is then added to the list. You can
     * continue this process until all the stream names have been collected in the list.
     *
     * ListStreams has a limit of five transactions per second per account.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_ListStreams.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#liststreams
     *
     * @param array{
     *   Limit?: null|int,
     *   ExclusiveStartStreamName?: null|string,
     *   NextToken?: null|string,
     *   '@region'?: string|null,
     * }|ListStreamsInput $input
     *
     * @throws LimitExceededException
     * @throws ExpiredNextTokenException
     * @throws InvalidArgumentException
     */
    public function listStreams($input = []): ListStreamsOutput
    {
        $input = ListStreamsInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListStreams', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'LimitExceededException' => LimitExceededException::class,
            'ExpiredNextTokenException' => ExpiredNextTokenException::class,
            'InvalidArgumentException' => InvalidArgumentException::class,
        ]]));

        return new ListStreamsOutput($response, $this, $input);
    }

    /**
     * Lists the tags for the specified Kinesis data stream. This operation has a limit of five transactions per second per
     * account.
     *
     * > When invoking this API, you must use either the `StreamARN` or the `StreamName` parameter, or both. It is
     * > recommended that you use the `StreamARN` input parameter when you invoke this API.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_ListTagsForStream.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#listtagsforstream
     *
     * @param array{
     *   StreamName?: null|string,
     *   ExclusiveStartTagKey?: null|string,
     *   Limit?: null|int,
     *   StreamARN?: null|string,
     *   '@region'?: string|null,
     * }|ListTagsForStreamInput $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidArgumentException
     * @throws LimitExceededException
     * @throws AccessDeniedException
     */
    public function listTagsForStream($input = []): ListTagsForStreamOutput
    {
        $input = ListTagsForStreamInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListTagsForStream', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidArgumentException' => InvalidArgumentException::class,
            'LimitExceededException' => LimitExceededException::class,
            'AccessDeniedException' => AccessDeniedException::class,
        ]]));

        return new ListTagsForStreamOutput($response);
    }

    /**
     * Merges two adjacent shards in a Kinesis data stream and combines them into a single shard to reduce the stream's
     * capacity to ingest and transport data. This API is only supported for the data streams with the provisioned capacity
     * mode. Two shards are considered adjacent if the union of the hash key ranges for the two shards form a contiguous set
     * with no gaps. For example, if you have two shards, one with a hash key range of 276...381 and the other with a hash
     * key range of 382...454, then you could merge these two shards into a single shard that would have a hash key range of
     * 276...454. After the merge, the single child shard receives data for all hash key values covered by the two parent
     * shards.
     *
     * > When invoking this API, you must use either the `StreamARN` or the `StreamName` parameter, or both. It is
     * > recommended that you use the `StreamARN` input parameter when you invoke this API.
     *
     * `MergeShards` is called when there is a need to reduce the overall capacity of a stream because of excess capacity
     * that is not being used. You must specify the shard to be merged and the adjacent shard for a stream. For more
     * information about merging shards, see Merge Two Shards [^1] in the *Amazon Kinesis Data Streams Developer Guide*.
     *
     * If the stream is in the `ACTIVE` state, you can call `MergeShards`. If a stream is in the `CREATING`, `UPDATING`, or
     * `DELETING` state, `MergeShards` returns a `ResourceInUseException`. If the specified stream does not exist,
     * `MergeShards` returns a `ResourceNotFoundException`.
     *
     * You can use DescribeStreamSummary to check the state of the stream, which is returned in `StreamStatus`.
     *
     * `MergeShards` is an asynchronous operation. Upon receiving a `MergeShards` request, Amazon Kinesis Data Streams
     * immediately returns a response and sets the `StreamStatus` to `UPDATING`. After the operation is completed, Kinesis
     * Data Streams sets the `StreamStatus` to `ACTIVE`. Read and write operations continue to work while the stream is in
     * the `UPDATING` state.
     *
     * You use DescribeStreamSummary and the ListShards APIs to determine the shard IDs that are specified in the
     * `MergeShards` request.
     *
     * If you try to operate on too many streams in parallel using CreateStream, DeleteStream, `MergeShards`, or SplitShard,
     * you receive a `LimitExceededException`.
     *
     * `MergeShards` has a limit of five transactions per second per account.
     *
     * [^1]: https://docs.aws.amazon.com/kinesis/latest/dev/kinesis-using-sdk-java-resharding-merge.html
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_MergeShards.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#mergeshards
     *
     * @param array{
     *   StreamName?: null|string,
     *   ShardToMerge: string,
     *   AdjacentShardToMerge: string,
     *   StreamARN?: null|string,
     *   '@region'?: string|null,
     * }|MergeShardsInput $input
     *
     * @throws ResourceNotFoundException
     * @throws ResourceInUseException
     * @throws InvalidArgumentException
     * @throws LimitExceededException
     * @throws ValidationException
     * @throws AccessDeniedException
     */
    public function mergeShards($input): Result
    {
        $input = MergeShardsInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'MergeShards', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ResourceInUseException' => ResourceInUseException::class,
            'InvalidArgumentException' => InvalidArgumentException::class,
            'LimitExceededException' => LimitExceededException::class,
            'ValidationException' => ValidationException::class,
            'AccessDeniedException' => AccessDeniedException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Writes a single data record into an Amazon Kinesis data stream. Call `PutRecord` to send data into the stream for
     * real-time ingestion and subsequent processing, one record at a time. Each shard can support writes up to 1,000
     * records per second, up to a maximum data write total of 1 MiB per second.
     *
     * > When invoking this API, you must use either the `StreamARN` or the `StreamName` parameter, or both. It is
     * > recommended that you use the `StreamARN` input parameter when you invoke this API.
     *
     * You must specify the name of the stream that captures, stores, and transports the data; a partition key; and the data
     * blob itself.
     *
     * The data blob can be any type of data; for example, a segment from a log file, geographic/location data, website
     * clickstream data, and so on.
     *
     * The partition key is used by Kinesis Data Streams to distribute data across shards. Kinesis Data Streams segregates
     * the data records that belong to a stream into multiple shards, using the partition key associated with each data
     * record to determine the shard to which a given data record belongs.
     *
     * Partition keys are Unicode strings, with a maximum length limit of 256 characters for each key. An MD5 hash function
     * is used to map partition keys to 128-bit integer values and to map associated data records to shards using the hash
     * key ranges of the shards. You can override hashing the partition key to determine the shard by explicitly specifying
     * a hash value using the `ExplicitHashKey` parameter. For more information, see Adding Data to a Stream [^1] in the
     * *Amazon Kinesis Data Streams Developer Guide*.
     *
     * `PutRecord` returns the shard ID of where the data record was placed and the sequence number that was assigned to the
     * data record.
     *
     * Sequence numbers increase over time and are specific to a shard within a stream, not across all shards within a
     * stream. To guarantee strictly increasing ordering, write serially to a shard and use the `SequenceNumberForOrdering`
     * parameter. For more information, see Adding Data to a Stream [^2] in the *Amazon Kinesis Data Streams Developer
     * Guide*.
     *
     * ! After you write a record to a stream, you cannot modify that record or its order within the stream.
     *
     * If a `PutRecord` request cannot be processed because of insufficient provisioned throughput on the shard involved in
     * the request, `PutRecord` throws `ProvisionedThroughputExceededException`.
     *
     * By default, data records are accessible for 24 hours from the time that they are added to a stream. You can use
     * IncreaseStreamRetentionPeriod or DecreaseStreamRetentionPeriod to modify this retention period.
     *
     * [^1]: https://docs.aws.amazon.com/kinesis/latest/dev/developing-producers-with-sdk.html#kinesis-using-sdk-java-add-data-to-stream
     * [^2]: https://docs.aws.amazon.com/kinesis/latest/dev/developing-producers-with-sdk.html#kinesis-using-sdk-java-add-data-to-stream
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_PutRecord.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#putrecord
     *
     * @param array{
     *   StreamName?: null|string,
     *   Data: string,
     *   PartitionKey: string,
     *   ExplicitHashKey?: null|string,
     *   SequenceNumberForOrdering?: null|string,
     *   StreamARN?: null|string,
     *   '@region'?: string|null,
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
     * @throws AccessDeniedException
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
            'AccessDeniedException' => AccessDeniedException::class,
        ]]));

        return new PutRecordOutput($response);
    }

    /**
     * Writes multiple data records into a Kinesis data stream in a single call (also referred to as a `PutRecords`
     * request). Use this operation to send data into the stream for data ingestion and processing.
     *
     * > When invoking this API, you must use either the `StreamARN` or the `StreamName` parameter, or both. It is
     * > recommended that you use the `StreamARN` input parameter when you invoke this API.
     *
     * Each `PutRecords` request can support up to 500 records. Each record in the request can be as large as 1 MiB, up to a
     * limit of 5 MiB for the entire request, including partition keys. Each shard can support writes up to 1,000 records
     * per second, up to a maximum data write total of 1 MiB per second.
     *
     * You must specify the name of the stream that captures, stores, and transports the data; and an array of request
     * `Records`, with each record in the array requiring a partition key and data blob. The record size limit applies to
     * the total size of the partition key and data blob.
     *
     * The data blob can be any type of data; for example, a segment from a log file, geographic/location data, website
     * clickstream data, and so on.
     *
     * The partition key is used by Kinesis Data Streams as input to a hash function that maps the partition key and
     * associated data to a specific shard. An MD5 hash function is used to map partition keys to 128-bit integer values and
     * to map associated data records to shards. As a result of this hashing mechanism, all data records with the same
     * partition key map to the same shard within the stream. For more information, see Adding Data to a Stream [^1] in the
     * *Amazon Kinesis Data Streams Developer Guide*.
     *
     * Each record in the `Records` array may include an optional parameter, `ExplicitHashKey`, which overrides the
     * partition key to shard mapping. This parameter allows a data producer to determine explicitly the shard where the
     * record is stored. For more information, see Adding Multiple Records with PutRecords [^2] in the *Amazon Kinesis Data
     * Streams Developer Guide*.
     *
     * The `PutRecords` response includes an array of response `Records`. Each record in the response array directly
     * correlates with a record in the request array using natural ordering, from the top to the bottom of the request and
     * response. The response `Records` array always includes the same number of records as the request array.
     *
     * The response `Records` array includes both successfully and unsuccessfully processed records. Kinesis Data Streams
     * attempts to process all records in each `PutRecords` request. A single record failure does not stop the processing of
     * subsequent records. As a result, PutRecords doesn't guarantee the ordering of records. If you need to read records in
     * the same order they are written to the stream, use PutRecord instead of `PutRecords`, and write to the same shard.
     *
     * A successfully processed record includes `ShardId` and `SequenceNumber` values. The `ShardId` parameter identifies
     * the shard in the stream where the record is stored. The `SequenceNumber` parameter is an identifier assigned to the
     * put record, unique to all records in the stream.
     *
     * An unsuccessfully processed record includes `ErrorCode` and `ErrorMessage` values. `ErrorCode` reflects the type of
     * error and can be one of the following values: `ProvisionedThroughputExceededException` or `InternalFailure`.
     * `ErrorMessage` provides more detailed information about the `ProvisionedThroughputExceededException` exception
     * including the account ID, stream name, and shard ID of the record that was throttled. For more information about
     * partially successful responses, see Adding Multiple Records with PutRecords [^3] in the *Amazon Kinesis Data Streams
     * Developer Guide*.
     *
     * ! After you write a record to a stream, you cannot modify that record or its order within the stream.
     *
     * By default, data records are accessible for 24 hours from the time that they are added to a stream. You can use
     * IncreaseStreamRetentionPeriod or DecreaseStreamRetentionPeriod to modify this retention period.
     *
     * [^1]: https://docs.aws.amazon.com/kinesis/latest/dev/developing-producers-with-sdk.html#kinesis-using-sdk-java-add-data-to-stream
     * [^2]: https://docs.aws.amazon.com/kinesis/latest/dev/developing-producers-with-sdk.html#kinesis-using-sdk-java-putrecords
     * [^3]: https://docs.aws.amazon.com/kinesis/latest/dev/kinesis-using-sdk-java-add-data-to-stream.html#kinesis-using-sdk-java-putrecords
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_PutRecords.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#putrecords
     *
     * @param array{
     *   Records: array<PutRecordsRequestEntry|array>,
     *   StreamName?: null|string,
     *   StreamARN?: null|string,
     *   '@region'?: string|null,
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
     * @throws AccessDeniedException
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
            'AccessDeniedException' => AccessDeniedException::class,
        ]]));

        return new PutRecordsOutput($response);
    }

    /**
     * Registers a consumer with a Kinesis data stream. When you use this operation, the consumer you register can then call
     * SubscribeToShard to receive data from the stream using enhanced fan-out, at a rate of up to 2 MiB per second for
     * every shard you subscribe to. This rate is unaffected by the total number of consumers that read from the same
     * stream.
     *
     * You can register up to 20 consumers per stream. A given consumer can only be registered with one stream at a time.
     *
     * For an example of how to use this operation, see Enhanced Fan-Out Using the Kinesis Data Streams API [^1].
     *
     * The use of this operation has a limit of five transactions per second per account. Also, only 5 consumers can be
     * created simultaneously. In other words, you cannot have more than 5 consumers in a `CREATING` status at the same
     * time. Registering a 6th consumer while there are 5 in a `CREATING` status results in a `LimitExceededException`.
     *
     * [^1]: https://docs.aws.amazon.com/streams/latest/dev/building-enhanced-consumers-api.html
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_RegisterStreamConsumer.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#registerstreamconsumer
     *
     * @param array{
     *   StreamARN: string,
     *   ConsumerName: string,
     *   '@region'?: string|null,
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
     * > When invoking this API, you must use either the `StreamARN` or the `StreamName` parameter, or both. It is
     * > recommended that you use the `StreamARN` input parameter when you invoke this API.
     *
     * If you specify a tag that does not exist, it is ignored.
     *
     * RemoveTagsFromStream has a limit of five transactions per second per account.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_RemoveTagsFromStream.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#removetagsfromstream
     *
     * @param array{
     *   StreamName?: null|string,
     *   TagKeys: string[],
     *   StreamARN?: null|string,
     *   '@region'?: string|null,
     * }|RemoveTagsFromStreamInput $input
     *
     * @throws ResourceNotFoundException
     * @throws ResourceInUseException
     * @throws InvalidArgumentException
     * @throws LimitExceededException
     * @throws AccessDeniedException
     */
    public function removeTagsFromStream($input): Result
    {
        $input = RemoveTagsFromStreamInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'RemoveTagsFromStream', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ResourceInUseException' => ResourceInUseException::class,
            'InvalidArgumentException' => InvalidArgumentException::class,
            'LimitExceededException' => LimitExceededException::class,
            'AccessDeniedException' => AccessDeniedException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Splits a shard into two new shards in the Kinesis data stream, to increase the stream's capacity to ingest and
     * transport data. `SplitShard` is called when there is a need to increase the overall capacity of a stream because of
     * an expected increase in the volume of data records being ingested. This API is only supported for the data streams
     * with the provisioned capacity mode.
     *
     * > When invoking this API, you must use either the `StreamARN` or the `StreamName` parameter, or both. It is
     * > recommended that you use the `StreamARN` input parameter when you invoke this API.
     *
     * You can also use `SplitShard` when a shard appears to be approaching its maximum utilization; for example, the
     * producers sending data into the specific shard are suddenly sending more than previously anticipated. You can also
     * call `SplitShard` to increase stream capacity, so that more Kinesis Data Streams applications can simultaneously read
     * data from the stream for real-time processing.
     *
     * You must specify the shard to be split and the new hash key, which is the position in the shard where the shard gets
     * split in two. In many cases, the new hash key might be the average of the beginning and ending hash key, but it can
     * be any hash key value in the range being mapped into the shard. For more information, see Split a Shard [^1] in the
     * *Amazon Kinesis Data Streams Developer Guide*.
     *
     * You can use DescribeStreamSummary and the ListShards APIs to determine the shard ID and hash key values for the
     * `ShardToSplit` and `NewStartingHashKey` parameters that are specified in the `SplitShard` request.
     *
     * `SplitShard` is an asynchronous operation. Upon receiving a `SplitShard` request, Kinesis Data Streams immediately
     * returns a response and sets the stream status to `UPDATING`. After the operation is completed, Kinesis Data Streams
     * sets the stream status to `ACTIVE`. Read and write operations continue to work while the stream is in the `UPDATING`
     * state.
     *
     * You can use DescribeStreamSummary to check the status of the stream, which is returned in `StreamStatus`. If the
     * stream is in the `ACTIVE` state, you can call `SplitShard`.
     *
     * If the specified stream does not exist, DescribeStreamSummary returns a `ResourceNotFoundException`. If you try to
     * create more shards than are authorized for your account, you receive a `LimitExceededException`.
     *
     * For the default shard limit for an Amazon Web Services account, see Kinesis Data Streams Limits [^2] in the *Amazon
     * Kinesis Data Streams Developer Guide*. To increase this limit, contact Amazon Web Services Support [^3].
     *
     * If you try to operate on too many streams simultaneously using CreateStream, DeleteStream, MergeShards, and/or
     * SplitShard, you receive a `LimitExceededException`.
     *
     * `SplitShard` has a limit of five transactions per second per account.
     *
     * [^1]: https://docs.aws.amazon.com/kinesis/latest/dev/kinesis-using-sdk-java-resharding-split.html
     * [^2]: https://docs.aws.amazon.com/kinesis/latest/dev/service-sizes-and-limits.html
     * [^3]: https://docs.aws.amazon.com/general/latest/gr/aws_service_limits.html
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_SplitShard.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#splitshard
     *
     * @param array{
     *   StreamName?: null|string,
     *   ShardToSplit: string,
     *   NewStartingHashKey: string,
     *   StreamARN?: null|string,
     *   '@region'?: string|null,
     * }|SplitShardInput $input
     *
     * @throws ResourceNotFoundException
     * @throws ResourceInUseException
     * @throws InvalidArgumentException
     * @throws LimitExceededException
     * @throws ValidationException
     * @throws AccessDeniedException
     */
    public function splitShard($input): Result
    {
        $input = SplitShardInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'SplitShard', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ResourceInUseException' => ResourceInUseException::class,
            'InvalidArgumentException' => InvalidArgumentException::class,
            'LimitExceededException' => LimitExceededException::class,
            'ValidationException' => ValidationException::class,
            'AccessDeniedException' => AccessDeniedException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Enables or updates server-side encryption using an Amazon Web Services KMS key for a specified stream.
     *
     * > When invoking this API, you must use either the `StreamARN` or the `StreamName` parameter, or both. It is
     * > recommended that you use the `StreamARN` input parameter when you invoke this API.
     *
     * Starting encryption is an asynchronous operation. Upon receiving the request, Kinesis Data Streams returns
     * immediately and sets the status of the stream to `UPDATING`. After the update is complete, Kinesis Data Streams sets
     * the status of the stream back to `ACTIVE`. Updating or applying encryption normally takes a few seconds to complete,
     * but it can take minutes. You can continue to read and write data to your stream while its status is `UPDATING`. Once
     * the status of the stream is `ACTIVE`, encryption begins for records written to the stream.
     *
     * API Limits: You can successfully apply a new Amazon Web Services KMS key for server-side encryption 25 times in a
     * rolling 24-hour period.
     *
     * Note: It can take up to 5 seconds after the stream is in an `ACTIVE` status before all records written to the stream
     * are encrypted. After you enable encryption, you can verify that encryption is applied by inspecting the API response
     * from `PutRecord` or `PutRecords`.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_StartStreamEncryption.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#startstreamencryption
     *
     * @param array{
     *   StreamName?: null|string,
     *   EncryptionType: EncryptionType::*,
     *   KeyId: string,
     *   StreamARN?: null|string,
     *   '@region'?: string|null,
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
     * @throws AccessDeniedException
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
            'AccessDeniedException' => AccessDeniedException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Disables server-side encryption for a specified stream.
     *
     * > When invoking this API, you must use either the `StreamARN` or the `StreamName` parameter, or both. It is
     * > recommended that you use the `StreamARN` input parameter when you invoke this API.
     *
     * Stopping encryption is an asynchronous operation. Upon receiving the request, Kinesis Data Streams returns
     * immediately and sets the status of the stream to `UPDATING`. After the update is complete, Kinesis Data Streams sets
     * the status of the stream back to `ACTIVE`. Stopping encryption normally takes a few seconds to complete, but it can
     * take minutes. You can continue to read and write data to your stream while its status is `UPDATING`. Once the status
     * of the stream is `ACTIVE`, records written to the stream are no longer encrypted by Kinesis Data Streams.
     *
     * API Limits: You can successfully disable server-side encryption 25 times in a rolling 24-hour period.
     *
     * Note: It can take up to 5 seconds after the stream is in an `ACTIVE` status before all records written to the stream
     * are no longer subject to encryption. After you disabled encryption, you can verify that encryption is not applied by
     * inspecting the API response from `PutRecord` or `PutRecords`.
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_StopStreamEncryption.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#stopstreamencryption
     *
     * @param array{
     *   StreamName?: null|string,
     *   EncryptionType: EncryptionType::*,
     *   KeyId: string,
     *   StreamARN?: null|string,
     *   '@region'?: string|null,
     * }|StopStreamEncryptionInput $input
     *
     * @throws InvalidArgumentException
     * @throws LimitExceededException
     * @throws ResourceInUseException
     * @throws ResourceNotFoundException
     * @throws AccessDeniedException
     */
    public function stopStreamEncryption($input): Result
    {
        $input = StopStreamEncryptionInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'StopStreamEncryption', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidArgumentException' => InvalidArgumentException::class,
            'LimitExceededException' => LimitExceededException::class,
            'ResourceInUseException' => ResourceInUseException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'AccessDeniedException' => AccessDeniedException::class,
        ]]));

        return new Result($response);
    }

    /**
     * @see describeStream
     *
     * @param array{
     *   StreamName?: null|string,
     *   Limit?: null|int,
     *   ExclusiveStartShardId?: null|string,
     *   StreamARN?: null|string,
     *   '@region'?: string|null,
     * }|DescribeStreamInput $input
     */
    public function streamExists($input = []): StreamExistsWaiter
    {
        $input = DescribeStreamInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DescribeStream', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'LimitExceededException' => LimitExceededException::class,
            'InvalidArgumentException' => InvalidArgumentException::class,
            'AccessDeniedException' => AccessDeniedException::class,
        ]]));

        return new StreamExistsWaiter($response, $this, $input);
    }

    /**
     * @see describeStream
     *
     * @param array{
     *   StreamName?: null|string,
     *   Limit?: null|int,
     *   ExclusiveStartShardId?: null|string,
     *   StreamARN?: null|string,
     *   '@region'?: string|null,
     * }|DescribeStreamInput $input
     */
    public function streamNotExists($input = []): StreamNotExistsWaiter
    {
        $input = DescribeStreamInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DescribeStream', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'LimitExceededException' => LimitExceededException::class,
            'InvalidArgumentException' => InvalidArgumentException::class,
            'AccessDeniedException' => AccessDeniedException::class,
        ]]));

        return new StreamNotExistsWaiter($response, $this, $input);
    }

    /**
     * Updates the shard count of the specified stream to the specified number of shards. This API is only supported for the
     * data streams with the provisioned capacity mode.
     *
     * > When invoking this API, you must use either the `StreamARN` or the `StreamName` parameter, or both. It is
     * > recommended that you use the `StreamARN` input parameter when you invoke this API.
     *
     * Updating the shard count is an asynchronous operation. Upon receiving the request, Kinesis Data Streams returns
     * immediately and sets the status of the stream to `UPDATING`. After the update is complete, Kinesis Data Streams sets
     * the status of the stream back to `ACTIVE`. Depending on the size of the stream, the scaling action could take a few
     * minutes to complete. You can continue to read and write data to your stream while its status is `UPDATING`.
     *
     * To update the shard count, Kinesis Data Streams performs splits or merges on individual shards. This can cause
     * short-lived shards to be created, in addition to the final shards. These short-lived shards count towards your total
     * shard limit for your account in the Region.
     *
     * When using this operation, we recommend that you specify a target shard count that is a multiple of 25% (25%, 50%,
     * 75%, 100%). You can specify any target value within your shard limit. However, if you specify a target that isn't a
     * multiple of 25%, the scaling action might take longer to complete.
     *
     * This operation has the following default limits. By default, you cannot do the following:
     *
     * - Scale more than ten times per rolling 24-hour period per stream
     * - Scale up to more than double your current shard count for a stream
     * - Scale down below half your current shard count for a stream
     * - Scale up to more than 10000 shards in a stream
     * - Scale a stream with more than 10000 shards down unless the result is less than 10000 shards
     * - Scale up to more than the shard limit for your account
     * - Make over 10 TPS. TPS over 10 will trigger the LimitExceededException
     *
     * For the default limits for an Amazon Web Services account, see Streams Limits [^1] in the *Amazon Kinesis Data
     * Streams Developer Guide*. To request an increase in the call rate limit, the shard limit for this API, or your
     * overall shard limit, use the limits form [^2].
     *
     * [^1]: https://docs.aws.amazon.com/kinesis/latest/dev/service-sizes-and-limits.html
     * [^2]: https://console.aws.amazon.com/support/v1#/case/create?issueType=service-limit-increase&amp;limitType=service-code-kinesis
     *
     * @see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_UpdateShardCount.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-kinesis-2013-12-02.html#updateshardcount
     *
     * @param array{
     *   StreamName?: null|string,
     *   TargetShardCount: int,
     *   ScalingType: ScalingType::*,
     *   StreamARN?: null|string,
     *   '@region'?: string|null,
     * }|UpdateShardCountInput $input
     *
     * @throws InvalidArgumentException
     * @throws LimitExceededException
     * @throws ResourceInUseException
     * @throws ResourceNotFoundException
     * @throws ValidationException
     * @throws AccessDeniedException
     */
    public function updateShardCount($input): UpdateShardCountOutput
    {
        $input = UpdateShardCountInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'UpdateShardCount', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidArgumentException' => InvalidArgumentException::class,
            'LimitExceededException' => LimitExceededException::class,
            'ResourceInUseException' => ResourceInUseException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ValidationException' => ValidationException::class,
            'AccessDeniedException' => AccessDeniedException::class,
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
            case 'fips-us-gov-east-1':
                return [
                    'endpoint' => 'https://kinesis.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'kinesis',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-west-1':
                return [
                    'endpoint' => 'https://kinesis.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'kinesis',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
            case 'us-iso-west-1':
                return [
                    'endpoint' => "https://kinesis.$region.c2s.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'kinesis',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
                return [
                    'endpoint' => 'https://kinesis.us-isob-east-1.sc2s.sgov.gov',
                    'signRegion' => 'us-isob-east-1',
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
