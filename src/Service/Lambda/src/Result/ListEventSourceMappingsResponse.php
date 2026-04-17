<?php

namespace AsyncAws\Lambda\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Lambda\Enum\EndPointType;
use AsyncAws\Lambda\Enum\EventSourceMappingMetric;
use AsyncAws\Lambda\Enum\EventSourceMappingSystemLogLevel;
use AsyncAws\Lambda\Enum\EventSourcePosition;
use AsyncAws\Lambda\Enum\FullDocument;
use AsyncAws\Lambda\Enum\FunctionResponseType;
use AsyncAws\Lambda\Enum\KafkaSchemaRegistryAuthType;
use AsyncAws\Lambda\Enum\KafkaSchemaValidationAttribute;
use AsyncAws\Lambda\Enum\SchemaRegistryEventRecordFormat;
use AsyncAws\Lambda\Enum\SourceAccessType;
use AsyncAws\Lambda\Input\ListEventSourceMappingsRequest;
use AsyncAws\Lambda\LambdaClient;
use AsyncAws\Lambda\ValueObject\AmazonManagedKafkaEventSourceConfig;
use AsyncAws\Lambda\ValueObject\DestinationConfig;
use AsyncAws\Lambda\ValueObject\DocumentDBEventSourceConfig;
use AsyncAws\Lambda\ValueObject\EventSourceMappingConfiguration;
use AsyncAws\Lambda\ValueObject\EventSourceMappingLoggingConfig;
use AsyncAws\Lambda\ValueObject\EventSourceMappingMetricsConfig;
use AsyncAws\Lambda\ValueObject\Filter;
use AsyncAws\Lambda\ValueObject\FilterCriteria;
use AsyncAws\Lambda\ValueObject\FilterCriteriaError;
use AsyncAws\Lambda\ValueObject\KafkaSchemaRegistryAccessConfig;
use AsyncAws\Lambda\ValueObject\KafkaSchemaRegistryConfig;
use AsyncAws\Lambda\ValueObject\KafkaSchemaValidationConfig;
use AsyncAws\Lambda\ValueObject\OnFailure;
use AsyncAws\Lambda\ValueObject\OnSuccess;
use AsyncAws\Lambda\ValueObject\ProvisionedPollerConfig;
use AsyncAws\Lambda\ValueObject\ScalingConfig;
use AsyncAws\Lambda\ValueObject\SelfManagedEventSource;
use AsyncAws\Lambda\ValueObject\SelfManagedKafkaEventSourceConfig;
use AsyncAws\Lambda\ValueObject\SourceAccessConfiguration;

/**
 * @implements \IteratorAggregate<EventSourceMappingConfiguration>
 */
class ListEventSourceMappingsResponse extends Result implements \IteratorAggregate
{
    /**
     * A pagination token that's returned when the response doesn't contain all event source mappings.
     *
     * @var string|null
     */
    private $nextMarker;

    /**
     * A list of event source mappings.
     *
     * @var EventSourceMappingConfiguration[]
     */
    private $eventSourceMappings;

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<EventSourceMappingConfiguration>
     */
    public function getEventSourceMappings(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->eventSourceMappings;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof LambdaClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListEventSourceMappingsRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextMarker) {
                $input->setMarker($page->nextMarker);

                $this->registerPrefetch($nextPage = $client->listEventSourceMappings($input));
            } else {
                $nextPage = null;
            }

            yield from $page->eventSourceMappings;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    /**
     * Iterates over EventSourceMappings.
     *
     * @return \Traversable<EventSourceMappingConfiguration>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getEventSourceMappings();
    }

    public function getNextMarker(): ?string
    {
        $this->initialize();

        return $this->nextMarker;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->nextMarker = isset($data['NextMarker']) ? (string) $data['NextMarker'] : null;
        $this->eventSourceMappings = empty($data['EventSourceMappings']) ? [] : $this->populateResultEventSourceMappingsList($data['EventSourceMappings']);
    }

    private function populateResultAmazonManagedKafkaEventSourceConfig(array $json): AmazonManagedKafkaEventSourceConfig
    {
        return new AmazonManagedKafkaEventSourceConfig([
            'ConsumerGroupId' => isset($json['ConsumerGroupId']) ? (string) $json['ConsumerGroupId'] : null,
            'SchemaRegistryConfig' => empty($json['SchemaRegistryConfig']) ? null : $this->populateResultKafkaSchemaRegistryConfig($json['SchemaRegistryConfig']),
        ]);
    }

    private function populateResultDestinationConfig(array $json): DestinationConfig
    {
        return new DestinationConfig([
            'OnSuccess' => empty($json['OnSuccess']) ? null : $this->populateResultOnSuccess($json['OnSuccess']),
            'OnFailure' => empty($json['OnFailure']) ? null : $this->populateResultOnFailure($json['OnFailure']),
        ]);
    }

    private function populateResultDocumentDBEventSourceConfig(array $json): DocumentDBEventSourceConfig
    {
        return new DocumentDBEventSourceConfig([
            'DatabaseName' => isset($json['DatabaseName']) ? (string) $json['DatabaseName'] : null,
            'CollectionName' => isset($json['CollectionName']) ? (string) $json['CollectionName'] : null,
            'FullDocument' => isset($json['FullDocument']) ? (!FullDocument::exists((string) $json['FullDocument']) ? FullDocument::UNKNOWN_TO_SDK : (string) $json['FullDocument']) : null,
        ]);
    }

    /**
     * @return string[]
     */
    private function populateResultEndpointLists(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return array<EndPointType::*, string[]>
     */
    private function populateResultEndpoints(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $name = (string) $name;
            if (!EndPointType::exists($name)) {
                $name = EndPointType::UNKNOWN_TO_SDK;
            }
            $items[$name] = $this->populateResultEndpointLists($value);
        }

        return $items;
    }

    private function populateResultEventSourceMappingConfiguration(array $json): EventSourceMappingConfiguration
    {
        return new EventSourceMappingConfiguration([
            'UUID' => isset($json['UUID']) ? (string) $json['UUID'] : null,
            'StartingPosition' => isset($json['StartingPosition']) ? (!EventSourcePosition::exists((string) $json['StartingPosition']) ? EventSourcePosition::UNKNOWN_TO_SDK : (string) $json['StartingPosition']) : null,
            'StartingPositionTimestamp' => isset($json['StartingPositionTimestamp']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['StartingPositionTimestamp']))) ? $d : null,
            'BatchSize' => isset($json['BatchSize']) ? (int) $json['BatchSize'] : null,
            'MaximumBatchingWindowInSeconds' => isset($json['MaximumBatchingWindowInSeconds']) ? (int) $json['MaximumBatchingWindowInSeconds'] : null,
            'ParallelizationFactor' => isset($json['ParallelizationFactor']) ? (int) $json['ParallelizationFactor'] : null,
            'EventSourceArn' => isset($json['EventSourceArn']) ? (string) $json['EventSourceArn'] : null,
            'FilterCriteria' => empty($json['FilterCriteria']) ? null : $this->populateResultFilterCriteria($json['FilterCriteria']),
            'FunctionArn' => isset($json['FunctionArn']) ? (string) $json['FunctionArn'] : null,
            'LastModified' => isset($json['LastModified']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['LastModified']))) ? $d : null,
            'LastProcessingResult' => isset($json['LastProcessingResult']) ? (string) $json['LastProcessingResult'] : null,
            'State' => isset($json['State']) ? (string) $json['State'] : null,
            'StateTransitionReason' => isset($json['StateTransitionReason']) ? (string) $json['StateTransitionReason'] : null,
            'DestinationConfig' => empty($json['DestinationConfig']) ? null : $this->populateResultDestinationConfig($json['DestinationConfig']),
            'Topics' => !isset($json['Topics']) ? null : $this->populateResultTopics($json['Topics']),
            'Queues' => !isset($json['Queues']) ? null : $this->populateResultQueues($json['Queues']),
            'SourceAccessConfigurations' => !isset($json['SourceAccessConfigurations']) ? null : $this->populateResultSourceAccessConfigurations($json['SourceAccessConfigurations']),
            'SelfManagedEventSource' => empty($json['SelfManagedEventSource']) ? null : $this->populateResultSelfManagedEventSource($json['SelfManagedEventSource']),
            'MaximumRecordAgeInSeconds' => isset($json['MaximumRecordAgeInSeconds']) ? (int) $json['MaximumRecordAgeInSeconds'] : null,
            'BisectBatchOnFunctionError' => isset($json['BisectBatchOnFunctionError']) ? filter_var($json['BisectBatchOnFunctionError'], \FILTER_VALIDATE_BOOLEAN) : null,
            'MaximumRetryAttempts' => isset($json['MaximumRetryAttempts']) ? (int) $json['MaximumRetryAttempts'] : null,
            'TumblingWindowInSeconds' => isset($json['TumblingWindowInSeconds']) ? (int) $json['TumblingWindowInSeconds'] : null,
            'FunctionResponseTypes' => !isset($json['FunctionResponseTypes']) ? null : $this->populateResultFunctionResponseTypeList($json['FunctionResponseTypes']),
            'AmazonManagedKafkaEventSourceConfig' => empty($json['AmazonManagedKafkaEventSourceConfig']) ? null : $this->populateResultAmazonManagedKafkaEventSourceConfig($json['AmazonManagedKafkaEventSourceConfig']),
            'SelfManagedKafkaEventSourceConfig' => empty($json['SelfManagedKafkaEventSourceConfig']) ? null : $this->populateResultSelfManagedKafkaEventSourceConfig($json['SelfManagedKafkaEventSourceConfig']),
            'ScalingConfig' => empty($json['ScalingConfig']) ? null : $this->populateResultScalingConfig($json['ScalingConfig']),
            'DocumentDBEventSourceConfig' => empty($json['DocumentDBEventSourceConfig']) ? null : $this->populateResultDocumentDBEventSourceConfig($json['DocumentDBEventSourceConfig']),
            'KMSKeyArn' => isset($json['KMSKeyArn']) ? (string) $json['KMSKeyArn'] : null,
            'FilterCriteriaError' => empty($json['FilterCriteriaError']) ? null : $this->populateResultFilterCriteriaError($json['FilterCriteriaError']),
            'EventSourceMappingArn' => isset($json['EventSourceMappingArn']) ? (string) $json['EventSourceMappingArn'] : null,
            'MetricsConfig' => empty($json['MetricsConfig']) ? null : $this->populateResultEventSourceMappingMetricsConfig($json['MetricsConfig']),
            'LoggingConfig' => empty($json['LoggingConfig']) ? null : $this->populateResultEventSourceMappingLoggingConfig($json['LoggingConfig']),
            'ProvisionedPollerConfig' => empty($json['ProvisionedPollerConfig']) ? null : $this->populateResultProvisionedPollerConfig($json['ProvisionedPollerConfig']),
        ]);
    }

    private function populateResultEventSourceMappingLoggingConfig(array $json): EventSourceMappingLoggingConfig
    {
        return new EventSourceMappingLoggingConfig([
            'SystemLogLevel' => isset($json['SystemLogLevel']) ? (!EventSourceMappingSystemLogLevel::exists((string) $json['SystemLogLevel']) ? EventSourceMappingSystemLogLevel::UNKNOWN_TO_SDK : (string) $json['SystemLogLevel']) : null,
        ]);
    }

    /**
     * @return list<EventSourceMappingMetric::*>
     */
    private function populateResultEventSourceMappingMetricList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                if (!EventSourceMappingMetric::exists($a)) {
                    $a = EventSourceMappingMetric::UNKNOWN_TO_SDK;
                }
                $items[] = $a;
            }
        }

        return $items;
    }

    private function populateResultEventSourceMappingMetricsConfig(array $json): EventSourceMappingMetricsConfig
    {
        return new EventSourceMappingMetricsConfig([
            'Metrics' => !isset($json['Metrics']) ? null : $this->populateResultEventSourceMappingMetricList($json['Metrics']),
        ]);
    }

    /**
     * @return EventSourceMappingConfiguration[]
     */
    private function populateResultEventSourceMappingsList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultEventSourceMappingConfiguration($item);
        }

        return $items;
    }

    private function populateResultFilter(array $json): Filter
    {
        return new Filter([
            'Pattern' => isset($json['Pattern']) ? (string) $json['Pattern'] : null,
        ]);
    }

    private function populateResultFilterCriteria(array $json): FilterCriteria
    {
        return new FilterCriteria([
            'Filters' => !isset($json['Filters']) ? null : $this->populateResultFilterList($json['Filters']),
        ]);
    }

    private function populateResultFilterCriteriaError(array $json): FilterCriteriaError
    {
        return new FilterCriteriaError([
            'ErrorCode' => isset($json['ErrorCode']) ? (string) $json['ErrorCode'] : null,
            'Message' => isset($json['Message']) ? (string) $json['Message'] : null,
        ]);
    }

    /**
     * @return Filter[]
     */
    private function populateResultFilterList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultFilter($item);
        }

        return $items;
    }

    /**
     * @return list<FunctionResponseType::*>
     */
    private function populateResultFunctionResponseTypeList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                if (!FunctionResponseType::exists($a)) {
                    $a = FunctionResponseType::UNKNOWN_TO_SDK;
                }
                $items[] = $a;
            }
        }

        return $items;
    }

    private function populateResultKafkaSchemaRegistryAccessConfig(array $json): KafkaSchemaRegistryAccessConfig
    {
        return new KafkaSchemaRegistryAccessConfig([
            'Type' => isset($json['Type']) ? (!KafkaSchemaRegistryAuthType::exists((string) $json['Type']) ? KafkaSchemaRegistryAuthType::UNKNOWN_TO_SDK : (string) $json['Type']) : null,
            'URI' => isset($json['URI']) ? (string) $json['URI'] : null,
        ]);
    }

    /**
     * @return KafkaSchemaRegistryAccessConfig[]
     */
    private function populateResultKafkaSchemaRegistryAccessConfigList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultKafkaSchemaRegistryAccessConfig($item);
        }

        return $items;
    }

    private function populateResultKafkaSchemaRegistryConfig(array $json): KafkaSchemaRegistryConfig
    {
        return new KafkaSchemaRegistryConfig([
            'SchemaRegistryURI' => isset($json['SchemaRegistryURI']) ? (string) $json['SchemaRegistryURI'] : null,
            'EventRecordFormat' => isset($json['EventRecordFormat']) ? (!SchemaRegistryEventRecordFormat::exists((string) $json['EventRecordFormat']) ? SchemaRegistryEventRecordFormat::UNKNOWN_TO_SDK : (string) $json['EventRecordFormat']) : null,
            'AccessConfigs' => !isset($json['AccessConfigs']) ? null : $this->populateResultKafkaSchemaRegistryAccessConfigList($json['AccessConfigs']),
            'SchemaValidationConfigs' => !isset($json['SchemaValidationConfigs']) ? null : $this->populateResultKafkaSchemaValidationConfigList($json['SchemaValidationConfigs']),
        ]);
    }

    private function populateResultKafkaSchemaValidationConfig(array $json): KafkaSchemaValidationConfig
    {
        return new KafkaSchemaValidationConfig([
            'Attribute' => isset($json['Attribute']) ? (!KafkaSchemaValidationAttribute::exists((string) $json['Attribute']) ? KafkaSchemaValidationAttribute::UNKNOWN_TO_SDK : (string) $json['Attribute']) : null,
        ]);
    }

    /**
     * @return KafkaSchemaValidationConfig[]
     */
    private function populateResultKafkaSchemaValidationConfigList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultKafkaSchemaValidationConfig($item);
        }

        return $items;
    }

    private function populateResultOnFailure(array $json): OnFailure
    {
        return new OnFailure([
            'Destination' => isset($json['Destination']) ? (string) $json['Destination'] : null,
        ]);
    }

    private function populateResultOnSuccess(array $json): OnSuccess
    {
        return new OnSuccess([
            'Destination' => isset($json['Destination']) ? (string) $json['Destination'] : null,
        ]);
    }

    private function populateResultProvisionedPollerConfig(array $json): ProvisionedPollerConfig
    {
        return new ProvisionedPollerConfig([
            'MinimumPollers' => isset($json['MinimumPollers']) ? (int) $json['MinimumPollers'] : null,
            'MaximumPollers' => isset($json['MaximumPollers']) ? (int) $json['MaximumPollers'] : null,
            'PollerGroupName' => isset($json['PollerGroupName']) ? (string) $json['PollerGroupName'] : null,
        ]);
    }

    /**
     * @return string[]
     */
    private function populateResultQueues(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    private function populateResultScalingConfig(array $json): ScalingConfig
    {
        return new ScalingConfig([
            'MaximumConcurrency' => isset($json['MaximumConcurrency']) ? (int) $json['MaximumConcurrency'] : null,
        ]);
    }

    private function populateResultSelfManagedEventSource(array $json): SelfManagedEventSource
    {
        return new SelfManagedEventSource([
            'Endpoints' => !isset($json['Endpoints']) ? null : $this->populateResultEndpoints($json['Endpoints']),
        ]);
    }

    private function populateResultSelfManagedKafkaEventSourceConfig(array $json): SelfManagedKafkaEventSourceConfig
    {
        return new SelfManagedKafkaEventSourceConfig([
            'ConsumerGroupId' => isset($json['ConsumerGroupId']) ? (string) $json['ConsumerGroupId'] : null,
            'SchemaRegistryConfig' => empty($json['SchemaRegistryConfig']) ? null : $this->populateResultKafkaSchemaRegistryConfig($json['SchemaRegistryConfig']),
        ]);
    }

    private function populateResultSourceAccessConfiguration(array $json): SourceAccessConfiguration
    {
        return new SourceAccessConfiguration([
            'Type' => isset($json['Type']) ? (!SourceAccessType::exists((string) $json['Type']) ? SourceAccessType::UNKNOWN_TO_SDK : (string) $json['Type']) : null,
            'URI' => isset($json['URI']) ? (string) $json['URI'] : null,
        ]);
    }

    /**
     * @return SourceAccessConfiguration[]
     */
    private function populateResultSourceAccessConfigurations(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultSourceAccessConfiguration($item);
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResultTopics(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }
}
