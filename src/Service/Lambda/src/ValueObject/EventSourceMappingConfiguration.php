<?php

namespace AsyncAws\Lambda\ValueObject;

use AsyncAws\Lambda\Enum\EventSourcePosition;
use AsyncAws\Lambda\Enum\FunctionResponseType;

/**
 * A mapping between an Amazon Web Services resource and a Lambda function. For details, see CreateEventSourceMapping.
 */
final class EventSourceMappingConfiguration
{
    /**
     * The identifier of the event source mapping.
     *
     * @var string|null
     */
    private $uuid;

    /**
     * The position in a stream from which to start reading. Required for Amazon Kinesis and Amazon DynamoDB Stream event
     * sources. `AT_TIMESTAMP` is supported only for Amazon Kinesis streams, Amazon DocumentDB, Amazon MSK, and self-managed
     * Apache Kafka.
     *
     * @var EventSourcePosition::*|null
     */
    private $startingPosition;

    /**
     * With `StartingPosition` set to `AT_TIMESTAMP`, the time from which to start reading. `StartingPositionTimestamp`
     * cannot be in the future.
     *
     * @var \DateTimeImmutable|null
     */
    private $startingPositionTimestamp;

    /**
     * The maximum number of records in each batch that Lambda pulls from your stream or queue and sends to your function.
     * Lambda passes all of the records in the batch to the function in a single call, up to the payload limit for
     * synchronous invocation (6 MB).
     *
     * Default value: Varies by service. For Amazon SQS, the default is 10. For all other services, the default is 100.
     *
     * Related setting: When you set `BatchSize` to a value greater than 10, you must set `MaximumBatchingWindowInSeconds`
     * to at least 1.
     *
     * @var int|null
     */
    private $batchSize;

    /**
     * The maximum amount of time, in seconds, that Lambda spends gathering records before invoking the function. You can
     * configure `MaximumBatchingWindowInSeconds` to any value from 0 seconds to 300 seconds in increments of seconds.
     *
     * For streams and Amazon SQS event sources, the default batching window is 0 seconds. For Amazon MSK, Self-managed
     * Apache Kafka, Amazon MQ, and DocumentDB event sources, the default batching window is 500 ms. Note that because you
     * can only change `MaximumBatchingWindowInSeconds` in increments of seconds, you cannot revert back to the 500 ms
     * default batching window after you have changed it. To restore the default batching window, you must create a new
     * event source mapping.
     *
     * Related setting: For streams and Amazon SQS event sources, when you set `BatchSize` to a value greater than 10, you
     * must set `MaximumBatchingWindowInSeconds` to at least 1.
     *
     * @var int|null
     */
    private $maximumBatchingWindowInSeconds;

    /**
     * (Kinesis and DynamoDB Streams only) The number of batches to process concurrently from each shard. The default value
     * is 1.
     *
     * @var int|null
     */
    private $parallelizationFactor;

    /**
     * The Amazon Resource Name (ARN) of the event source.
     *
     * @var string|null
     */
    private $eventSourceArn;

    /**
     * An object that defines the filter criteria that determine whether Lambda should process an event. For more
     * information, see Lambda event filtering [^1].
     *
     * If filter criteria is encrypted, this field shows up as `null` in the response of ListEventSourceMapping API calls.
     * You can view this field in plaintext in the response of GetEventSourceMapping and DeleteEventSourceMapping calls if
     * you have `kms:Decrypt` permissions for the correct KMS key.
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/invocation-eventfiltering.html
     *
     * @var FilterCriteria|null
     */
    private $filterCriteria;

    /**
     * The ARN of the Lambda function.
     *
     * @var string|null
     */
    private $functionArn;

    /**
     * The date that the event source mapping was last updated or that its state changed.
     *
     * @var \DateTimeImmutable|null
     */
    private $lastModified;

    /**
     * The result of the event source mapping's last processing attempt.
     *
     * @var string|null
     */
    private $lastProcessingResult;

    /**
     * The state of the event source mapping. It can be one of the following: `Creating`, `Enabling`, `Enabled`,
     * `Disabling`, `Disabled`, `Updating`, or `Deleting`.
     *
     * @var string|null
     */
    private $state;

    /**
     * Indicates whether a user or Lambda made the last change to the event source mapping.
     *
     * @var string|null
     */
    private $stateTransitionReason;

    /**
     * (Kinesis, DynamoDB Streams, Amazon MSK, and self-managed Apache Kafka) A configuration object that specifies the
     * destination of an event after Lambda processes it.
     *
     * @var DestinationConfig|null
     */
    private $destinationConfig;

    /**
     * The name of the Kafka topic.
     *
     * @var string[]|null
     */
    private $topics;

    /**
     * (Amazon MQ) The name of the Amazon MQ broker destination queue to consume.
     *
     * @var string[]|null
     */
    private $queues;

    /**
     * An array of the authentication protocol, VPC components, or virtual host to secure and define your event source.
     *
     * @var SourceAccessConfiguration[]|null
     */
    private $sourceAccessConfigurations;

    /**
     * The self-managed Apache Kafka cluster for your event source.
     *
     * @var SelfManagedEventSource|null
     */
    private $selfManagedEventSource;

    /**
     * (Kinesis, DynamoDB Streams, Amazon MSK, and self-managed Apache Kafka) Discard records older than the specified age.
     * The default value is -1, which sets the maximum age to infinite. When the value is set to infinite, Lambda never
     * discards old records.
     *
     * > The minimum valid value for maximum record age is 60s. Although values less than 60 and greater than -1 fall within
     * > the parameter's absolute range, they are not allowed
     *
     * @var int|null
     */
    private $maximumRecordAgeInSeconds;

    /**
     * (Kinesis, DynamoDB Streams, Amazon MSK, and self-managed Apache Kafka) If the function returns an error, split the
     * batch in two and retry. The default value is false.
     *
     * @var bool|null
     */
    private $bisectBatchOnFunctionError;

    /**
     * (Kinesis, DynamoDB Streams, Amazon MSK, and self-managed Apache Kafka) Discard records after the specified number of
     * retries. The default value is -1, which sets the maximum number of retries to infinite. When MaximumRetryAttempts is
     * infinite, Lambda retries failed records until the record expires in the event source.
     *
     * @var int|null
     */
    private $maximumRetryAttempts;

    /**
     * (Kinesis and DynamoDB Streams only) The duration in seconds of a processing window for DynamoDB and Kinesis Streams
     * event sources. A value of 0 seconds indicates no tumbling window.
     *
     * @var int|null
     */
    private $tumblingWindowInSeconds;

    /**
     * (Kinesis, DynamoDB Streams, Amazon MSK, self-managed Apache Kafka, and Amazon SQS) A list of current response type
     * enums applied to the event source mapping.
     *
     * @var list<FunctionResponseType::*>|null
     */
    private $functionResponseTypes;

    /**
     * Specific configuration settings for an Amazon Managed Streaming for Apache Kafka (Amazon MSK) event source.
     *
     * @var AmazonManagedKafkaEventSourceConfig|null
     */
    private $amazonManagedKafkaEventSourceConfig;

    /**
     * Specific configuration settings for a self-managed Apache Kafka event source.
     *
     * @var SelfManagedKafkaEventSourceConfig|null
     */
    private $selfManagedKafkaEventSourceConfig;

    /**
     * (Amazon SQS only) The scaling configuration for the event source. For more information, see Configuring maximum
     * concurrency for Amazon SQS event sources [^1].
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/with-sqs.html#events-sqs-max-concurrency
     *
     * @var ScalingConfig|null
     */
    private $scalingConfig;

    /**
     * Specific configuration settings for a DocumentDB event source.
     *
     * @var DocumentDBEventSourceConfig|null
     */
    private $documentDbEventSourceConfig;

    /**
     * The ARN of the Key Management Service (KMS) customer managed key that Lambda uses to encrypt your function's filter
     * criteria [^1].
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/invocation-eventfiltering.html#filtering-basics
     *
     * @var string|null
     */
    private $kmsKeyArn;

    /**
     * An object that contains details about an error related to filter criteria encryption.
     *
     * @var FilterCriteriaError|null
     */
    private $filterCriteriaError;

    /**
     * The Amazon Resource Name (ARN) of the event source mapping.
     *
     * @var string|null
     */
    private $eventSourceMappingArn;

    /**
     * The metrics configuration for your event source. For more information, see Event source mapping metrics [^1].
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/monitoring-metrics-types.html#event-source-mapping-metrics
     *
     * @var EventSourceMappingMetricsConfig|null
     */
    private $metricsConfig;

    /**
     * (Amazon MSK, and self-managed Apache Kafka only) The logging configuration for your event source. For more
     * information, see Event source mapping logging [^1].
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/esm-logging.html
     *
     * @var EventSourceMappingLoggingConfig|null
     */
    private $loggingConfig;

    /**
     * (Amazon SQS, Amazon MSK, and self-managed Apache Kafka only) The provisioned mode configuration for the event source.
     * For more information, see provisioned mode [^1].
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/invocation-eventsourcemapping.html#invocation-eventsourcemapping-provisioned-mode
     *
     * @var ProvisionedPollerConfig|null
     */
    private $provisionedPollerConfig;

    /**
     * @param array{
     *   UUID?: string|null,
     *   StartingPosition?: EventSourcePosition::*|null,
     *   StartingPositionTimestamp?: \DateTimeImmutable|null,
     *   BatchSize?: int|null,
     *   MaximumBatchingWindowInSeconds?: int|null,
     *   ParallelizationFactor?: int|null,
     *   EventSourceArn?: string|null,
     *   FilterCriteria?: FilterCriteria|array|null,
     *   FunctionArn?: string|null,
     *   LastModified?: \DateTimeImmutable|null,
     *   LastProcessingResult?: string|null,
     *   State?: string|null,
     *   StateTransitionReason?: string|null,
     *   DestinationConfig?: DestinationConfig|array|null,
     *   Topics?: string[]|null,
     *   Queues?: string[]|null,
     *   SourceAccessConfigurations?: array<SourceAccessConfiguration|array>|null,
     *   SelfManagedEventSource?: SelfManagedEventSource|array|null,
     *   MaximumRecordAgeInSeconds?: int|null,
     *   BisectBatchOnFunctionError?: bool|null,
     *   MaximumRetryAttempts?: int|null,
     *   TumblingWindowInSeconds?: int|null,
     *   FunctionResponseTypes?: array<FunctionResponseType::*>|null,
     *   AmazonManagedKafkaEventSourceConfig?: AmazonManagedKafkaEventSourceConfig|array|null,
     *   SelfManagedKafkaEventSourceConfig?: SelfManagedKafkaEventSourceConfig|array|null,
     *   ScalingConfig?: ScalingConfig|array|null,
     *   DocumentDBEventSourceConfig?: DocumentDBEventSourceConfig|array|null,
     *   KMSKeyArn?: string|null,
     *   FilterCriteriaError?: FilterCriteriaError|array|null,
     *   EventSourceMappingArn?: string|null,
     *   MetricsConfig?: EventSourceMappingMetricsConfig|array|null,
     *   LoggingConfig?: EventSourceMappingLoggingConfig|array|null,
     *   ProvisionedPollerConfig?: ProvisionedPollerConfig|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->uuid = $input['UUID'] ?? null;
        $this->startingPosition = $input['StartingPosition'] ?? null;
        $this->startingPositionTimestamp = $input['StartingPositionTimestamp'] ?? null;
        $this->batchSize = $input['BatchSize'] ?? null;
        $this->maximumBatchingWindowInSeconds = $input['MaximumBatchingWindowInSeconds'] ?? null;
        $this->parallelizationFactor = $input['ParallelizationFactor'] ?? null;
        $this->eventSourceArn = $input['EventSourceArn'] ?? null;
        $this->filterCriteria = isset($input['FilterCriteria']) ? FilterCriteria::create($input['FilterCriteria']) : null;
        $this->functionArn = $input['FunctionArn'] ?? null;
        $this->lastModified = $input['LastModified'] ?? null;
        $this->lastProcessingResult = $input['LastProcessingResult'] ?? null;
        $this->state = $input['State'] ?? null;
        $this->stateTransitionReason = $input['StateTransitionReason'] ?? null;
        $this->destinationConfig = isset($input['DestinationConfig']) ? DestinationConfig::create($input['DestinationConfig']) : null;
        $this->topics = $input['Topics'] ?? null;
        $this->queues = $input['Queues'] ?? null;
        $this->sourceAccessConfigurations = isset($input['SourceAccessConfigurations']) ? array_map([SourceAccessConfiguration::class, 'create'], $input['SourceAccessConfigurations']) : null;
        $this->selfManagedEventSource = isset($input['SelfManagedEventSource']) ? SelfManagedEventSource::create($input['SelfManagedEventSource']) : null;
        $this->maximumRecordAgeInSeconds = $input['MaximumRecordAgeInSeconds'] ?? null;
        $this->bisectBatchOnFunctionError = $input['BisectBatchOnFunctionError'] ?? null;
        $this->maximumRetryAttempts = $input['MaximumRetryAttempts'] ?? null;
        $this->tumblingWindowInSeconds = $input['TumblingWindowInSeconds'] ?? null;
        $this->functionResponseTypes = $input['FunctionResponseTypes'] ?? null;
        $this->amazonManagedKafkaEventSourceConfig = isset($input['AmazonManagedKafkaEventSourceConfig']) ? AmazonManagedKafkaEventSourceConfig::create($input['AmazonManagedKafkaEventSourceConfig']) : null;
        $this->selfManagedKafkaEventSourceConfig = isset($input['SelfManagedKafkaEventSourceConfig']) ? SelfManagedKafkaEventSourceConfig::create($input['SelfManagedKafkaEventSourceConfig']) : null;
        $this->scalingConfig = isset($input['ScalingConfig']) ? ScalingConfig::create($input['ScalingConfig']) : null;
        $this->documentDbEventSourceConfig = isset($input['DocumentDBEventSourceConfig']) ? DocumentDBEventSourceConfig::create($input['DocumentDBEventSourceConfig']) : null;
        $this->kmsKeyArn = $input['KMSKeyArn'] ?? null;
        $this->filterCriteriaError = isset($input['FilterCriteriaError']) ? FilterCriteriaError::create($input['FilterCriteriaError']) : null;
        $this->eventSourceMappingArn = $input['EventSourceMappingArn'] ?? null;
        $this->metricsConfig = isset($input['MetricsConfig']) ? EventSourceMappingMetricsConfig::create($input['MetricsConfig']) : null;
        $this->loggingConfig = isset($input['LoggingConfig']) ? EventSourceMappingLoggingConfig::create($input['LoggingConfig']) : null;
        $this->provisionedPollerConfig = isset($input['ProvisionedPollerConfig']) ? ProvisionedPollerConfig::create($input['ProvisionedPollerConfig']) : null;
    }

    /**
     * @param array{
     *   UUID?: string|null,
     *   StartingPosition?: EventSourcePosition::*|null,
     *   StartingPositionTimestamp?: \DateTimeImmutable|null,
     *   BatchSize?: int|null,
     *   MaximumBatchingWindowInSeconds?: int|null,
     *   ParallelizationFactor?: int|null,
     *   EventSourceArn?: string|null,
     *   FilterCriteria?: FilterCriteria|array|null,
     *   FunctionArn?: string|null,
     *   LastModified?: \DateTimeImmutable|null,
     *   LastProcessingResult?: string|null,
     *   State?: string|null,
     *   StateTransitionReason?: string|null,
     *   DestinationConfig?: DestinationConfig|array|null,
     *   Topics?: string[]|null,
     *   Queues?: string[]|null,
     *   SourceAccessConfigurations?: array<SourceAccessConfiguration|array>|null,
     *   SelfManagedEventSource?: SelfManagedEventSource|array|null,
     *   MaximumRecordAgeInSeconds?: int|null,
     *   BisectBatchOnFunctionError?: bool|null,
     *   MaximumRetryAttempts?: int|null,
     *   TumblingWindowInSeconds?: int|null,
     *   FunctionResponseTypes?: array<FunctionResponseType::*>|null,
     *   AmazonManagedKafkaEventSourceConfig?: AmazonManagedKafkaEventSourceConfig|array|null,
     *   SelfManagedKafkaEventSourceConfig?: SelfManagedKafkaEventSourceConfig|array|null,
     *   ScalingConfig?: ScalingConfig|array|null,
     *   DocumentDBEventSourceConfig?: DocumentDBEventSourceConfig|array|null,
     *   KMSKeyArn?: string|null,
     *   FilterCriteriaError?: FilterCriteriaError|array|null,
     *   EventSourceMappingArn?: string|null,
     *   MetricsConfig?: EventSourceMappingMetricsConfig|array|null,
     *   LoggingConfig?: EventSourceMappingLoggingConfig|array|null,
     *   ProvisionedPollerConfig?: ProvisionedPollerConfig|array|null,
     * }|EventSourceMappingConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAmazonManagedKafkaEventSourceConfig(): ?AmazonManagedKafkaEventSourceConfig
    {
        return $this->amazonManagedKafkaEventSourceConfig;
    }

    public function getBatchSize(): ?int
    {
        return $this->batchSize;
    }

    public function getBisectBatchOnFunctionError(): ?bool
    {
        return $this->bisectBatchOnFunctionError;
    }

    public function getDestinationConfig(): ?DestinationConfig
    {
        return $this->destinationConfig;
    }

    public function getDocumentDbEventSourceConfig(): ?DocumentDBEventSourceConfig
    {
        return $this->documentDbEventSourceConfig;
    }

    public function getEventSourceArn(): ?string
    {
        return $this->eventSourceArn;
    }

    public function getEventSourceMappingArn(): ?string
    {
        return $this->eventSourceMappingArn;
    }

    public function getFilterCriteria(): ?FilterCriteria
    {
        return $this->filterCriteria;
    }

    public function getFilterCriteriaError(): ?FilterCriteriaError
    {
        return $this->filterCriteriaError;
    }

    public function getFunctionArn(): ?string
    {
        return $this->functionArn;
    }

    /**
     * @return list<FunctionResponseType::*>
     */
    public function getFunctionResponseTypes(): array
    {
        return $this->functionResponseTypes ?? [];
    }

    public function getKmsKeyArn(): ?string
    {
        return $this->kmsKeyArn;
    }

    public function getLastModified(): ?\DateTimeImmutable
    {
        return $this->lastModified;
    }

    public function getLastProcessingResult(): ?string
    {
        return $this->lastProcessingResult;
    }

    public function getLoggingConfig(): ?EventSourceMappingLoggingConfig
    {
        return $this->loggingConfig;
    }

    public function getMaximumBatchingWindowInSeconds(): ?int
    {
        return $this->maximumBatchingWindowInSeconds;
    }

    public function getMaximumRecordAgeInSeconds(): ?int
    {
        return $this->maximumRecordAgeInSeconds;
    }

    public function getMaximumRetryAttempts(): ?int
    {
        return $this->maximumRetryAttempts;
    }

    public function getMetricsConfig(): ?EventSourceMappingMetricsConfig
    {
        return $this->metricsConfig;
    }

    public function getParallelizationFactor(): ?int
    {
        return $this->parallelizationFactor;
    }

    public function getProvisionedPollerConfig(): ?ProvisionedPollerConfig
    {
        return $this->provisionedPollerConfig;
    }

    /**
     * @return string[]
     */
    public function getQueues(): array
    {
        return $this->queues ?? [];
    }

    public function getScalingConfig(): ?ScalingConfig
    {
        return $this->scalingConfig;
    }

    public function getSelfManagedEventSource(): ?SelfManagedEventSource
    {
        return $this->selfManagedEventSource;
    }

    public function getSelfManagedKafkaEventSourceConfig(): ?SelfManagedKafkaEventSourceConfig
    {
        return $this->selfManagedKafkaEventSourceConfig;
    }

    /**
     * @return SourceAccessConfiguration[]
     */
    public function getSourceAccessConfigurations(): array
    {
        return $this->sourceAccessConfigurations ?? [];
    }

    /**
     * @return EventSourcePosition::*|null
     */
    public function getStartingPosition(): ?string
    {
        return $this->startingPosition;
    }

    public function getStartingPositionTimestamp(): ?\DateTimeImmutable
    {
        return $this->startingPositionTimestamp;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function getStateTransitionReason(): ?string
    {
        return $this->stateTransitionReason;
    }

    /**
     * @return string[]
     */
    public function getTopics(): array
    {
        return $this->topics ?? [];
    }

    public function getTumblingWindowInSeconds(): ?int
    {
        return $this->tumblingWindowInSeconds;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }
}
