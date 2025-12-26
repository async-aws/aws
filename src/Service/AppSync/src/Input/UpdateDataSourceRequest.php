<?php

namespace AsyncAws\AppSync\Input;

use AsyncAws\AppSync\Enum\DataSourceLevelMetricsConfig;
use AsyncAws\AppSync\Enum\DataSourceType;
use AsyncAws\AppSync\ValueObject\DynamodbDataSourceConfig;
use AsyncAws\AppSync\ValueObject\ElasticsearchDataSourceConfig;
use AsyncAws\AppSync\ValueObject\EventBridgeDataSourceConfig;
use AsyncAws\AppSync\ValueObject\HttpDataSourceConfig;
use AsyncAws\AppSync\ValueObject\LambdaDataSourceConfig;
use AsyncAws\AppSync\ValueObject\OpenSearchServiceDataSourceConfig;
use AsyncAws\AppSync\ValueObject\RelationalDatabaseDataSourceConfig;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class UpdateDataSourceRequest extends Input
{
    /**
     * The API ID.
     *
     * @required
     *
     * @var string|null
     */
    private $apiId;

    /**
     * The new name for the data source.
     *
     * @required
     *
     * @var string|null
     */
    private $name;

    /**
     * The new description for the data source.
     *
     * @var string|null
     */
    private $description;

    /**
     * The new data source type.
     *
     * @required
     *
     * @var DataSourceType::*|null
     */
    private $type;

    /**
     * The new service role Amazon Resource Name (ARN) for the data source.
     *
     * @var string|null
     */
    private $serviceRoleArn;

    /**
     * The new Amazon DynamoDB configuration.
     *
     * @var DynamodbDataSourceConfig|null
     */
    private $dynamodbConfig;

    /**
     * The new Lambda configuration.
     *
     * @var LambdaDataSourceConfig|null
     */
    private $lambdaConfig;

    /**
     * The new OpenSearch configuration.
     *
     * As of September 2021, Amazon Elasticsearch service is Amazon OpenSearch Service. This configuration is deprecated.
     * Instead, use UpdateDataSourceRequest$openSearchServiceConfig to update an OpenSearch data source.
     *
     * @var ElasticsearchDataSourceConfig|null
     */
    private $elasticsearchConfig;

    /**
     * The new OpenSearch configuration.
     *
     * @var OpenSearchServiceDataSourceConfig|null
     */
    private $openSearchServiceConfig;

    /**
     * The new HTTP endpoint configuration.
     *
     * @var HttpDataSourceConfig|null
     */
    private $httpConfig;

    /**
     * The new relational database configuration.
     *
     * @var RelationalDatabaseDataSourceConfig|null
     */
    private $relationalDatabaseConfig;

    /**
     * The new Amazon EventBridge settings.
     *
     * @var EventBridgeDataSourceConfig|null
     */
    private $eventBridgeConfig;

    /**
     * Enables or disables enhanced data source metrics for specified data sources. Note that `metricsConfig` won't be used
     * unless the `dataSourceLevelMetricsBehavior` value is set to `PER_DATA_SOURCE_METRICS`. If the
     * `dataSourceLevelMetricsBehavior` is set to `FULL_REQUEST_DATA_SOURCE_METRICS` instead, `metricsConfig` will be
     * ignored. However, you can still set its value.
     *
     * `metricsConfig` can be `ENABLED` or `DISABLED`.
     *
     * @var DataSourceLevelMetricsConfig::*|null
     */
    private $metricsConfig;

    /**
     * @param array{
     *   apiId?: string,
     *   name?: string,
     *   description?: string|null,
     *   type?: DataSourceType::*,
     *   serviceRoleArn?: string|null,
     *   dynamodbConfig?: DynamodbDataSourceConfig|array|null,
     *   lambdaConfig?: LambdaDataSourceConfig|array|null,
     *   elasticsearchConfig?: ElasticsearchDataSourceConfig|array|null,
     *   openSearchServiceConfig?: OpenSearchServiceDataSourceConfig|array|null,
     *   httpConfig?: HttpDataSourceConfig|array|null,
     *   relationalDatabaseConfig?: RelationalDatabaseDataSourceConfig|array|null,
     *   eventBridgeConfig?: EventBridgeDataSourceConfig|array|null,
     *   metricsConfig?: DataSourceLevelMetricsConfig::*|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->apiId = $input['apiId'] ?? null;
        $this->name = $input['name'] ?? null;
        $this->description = $input['description'] ?? null;
        $this->type = $input['type'] ?? null;
        $this->serviceRoleArn = $input['serviceRoleArn'] ?? null;
        $this->dynamodbConfig = isset($input['dynamodbConfig']) ? DynamodbDataSourceConfig::create($input['dynamodbConfig']) : null;
        $this->lambdaConfig = isset($input['lambdaConfig']) ? LambdaDataSourceConfig::create($input['lambdaConfig']) : null;
        $this->elasticsearchConfig = isset($input['elasticsearchConfig']) ? ElasticsearchDataSourceConfig::create($input['elasticsearchConfig']) : null;
        $this->openSearchServiceConfig = isset($input['openSearchServiceConfig']) ? OpenSearchServiceDataSourceConfig::create($input['openSearchServiceConfig']) : null;
        $this->httpConfig = isset($input['httpConfig']) ? HttpDataSourceConfig::create($input['httpConfig']) : null;
        $this->relationalDatabaseConfig = isset($input['relationalDatabaseConfig']) ? RelationalDatabaseDataSourceConfig::create($input['relationalDatabaseConfig']) : null;
        $this->eventBridgeConfig = isset($input['eventBridgeConfig']) ? EventBridgeDataSourceConfig::create($input['eventBridgeConfig']) : null;
        $this->metricsConfig = $input['metricsConfig'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   apiId?: string,
     *   name?: string,
     *   description?: string|null,
     *   type?: DataSourceType::*,
     *   serviceRoleArn?: string|null,
     *   dynamodbConfig?: DynamodbDataSourceConfig|array|null,
     *   lambdaConfig?: LambdaDataSourceConfig|array|null,
     *   elasticsearchConfig?: ElasticsearchDataSourceConfig|array|null,
     *   openSearchServiceConfig?: OpenSearchServiceDataSourceConfig|array|null,
     *   httpConfig?: HttpDataSourceConfig|array|null,
     *   relationalDatabaseConfig?: RelationalDatabaseDataSourceConfig|array|null,
     *   eventBridgeConfig?: EventBridgeDataSourceConfig|array|null,
     *   metricsConfig?: DataSourceLevelMetricsConfig::*|null,
     *   '@region'?: string|null,
     * }|UpdateDataSourceRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getApiId(): ?string
    {
        return $this->apiId;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getDynamodbConfig(): ?DynamodbDataSourceConfig
    {
        return $this->dynamodbConfig;
    }

    public function getElasticsearchConfig(): ?ElasticsearchDataSourceConfig
    {
        return $this->elasticsearchConfig;
    }

    public function getEventBridgeConfig(): ?EventBridgeDataSourceConfig
    {
        return $this->eventBridgeConfig;
    }

    public function getHttpConfig(): ?HttpDataSourceConfig
    {
        return $this->httpConfig;
    }

    public function getLambdaConfig(): ?LambdaDataSourceConfig
    {
        return $this->lambdaConfig;
    }

    /**
     * @return DataSourceLevelMetricsConfig::*|null
     */
    public function getMetricsConfig(): ?string
    {
        return $this->metricsConfig;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getOpenSearchServiceConfig(): ?OpenSearchServiceDataSourceConfig
    {
        return $this->openSearchServiceConfig;
    }

    public function getRelationalDatabaseConfig(): ?RelationalDatabaseDataSourceConfig
    {
        return $this->relationalDatabaseConfig;
    }

    public function getServiceRoleArn(): ?string
    {
        return $this->serviceRoleArn;
    }

    /**
     * @return DataSourceType::*|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uri = [];
        if (null === $v = $this->apiId) {
            throw new InvalidArgument(\sprintf('Missing parameter "apiId" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['apiId'] = $v;
        if (null === $v = $this->name) {
            throw new InvalidArgument(\sprintf('Missing parameter "name" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['name'] = $v;
        $uriString = '/v1/apis/' . rawurlencode($uri['apiId']) . '/datasources/' . rawurlencode($uri['name']);

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setApiId(?string $value): self
    {
        $this->apiId = $value;

        return $this;
    }

    public function setDescription(?string $value): self
    {
        $this->description = $value;

        return $this;
    }

    public function setDynamodbConfig(?DynamodbDataSourceConfig $value): self
    {
        $this->dynamodbConfig = $value;

        return $this;
    }

    public function setElasticsearchConfig(?ElasticsearchDataSourceConfig $value): self
    {
        $this->elasticsearchConfig = $value;

        return $this;
    }

    public function setEventBridgeConfig(?EventBridgeDataSourceConfig $value): self
    {
        $this->eventBridgeConfig = $value;

        return $this;
    }

    public function setHttpConfig(?HttpDataSourceConfig $value): self
    {
        $this->httpConfig = $value;

        return $this;
    }

    public function setLambdaConfig(?LambdaDataSourceConfig $value): self
    {
        $this->lambdaConfig = $value;

        return $this;
    }

    /**
     * @param DataSourceLevelMetricsConfig::*|null $value
     */
    public function setMetricsConfig(?string $value): self
    {
        $this->metricsConfig = $value;

        return $this;
    }

    public function setName(?string $value): self
    {
        $this->name = $value;

        return $this;
    }

    public function setOpenSearchServiceConfig(?OpenSearchServiceDataSourceConfig $value): self
    {
        $this->openSearchServiceConfig = $value;

        return $this;
    }

    public function setRelationalDatabaseConfig(?RelationalDatabaseDataSourceConfig $value): self
    {
        $this->relationalDatabaseConfig = $value;

        return $this;
    }

    public function setServiceRoleArn(?string $value): self
    {
        $this->serviceRoleArn = $value;

        return $this;
    }

    /**
     * @param DataSourceType::*|null $value
     */
    public function setType(?string $value): self
    {
        $this->type = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];

        if (null !== $v = $this->description) {
            $payload['description'] = $v;
        }
        if (null === $v = $this->type) {
            throw new InvalidArgument(\sprintf('Missing parameter "type" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!DataSourceType::exists($v)) {
            /** @psalm-suppress NoValue */
            throw new InvalidArgument(\sprintf('Invalid parameter "type" for "%s". The value "%s" is not a valid "DataSourceType".', __CLASS__, $v));
        }
        $payload['type'] = $v;
        if (null !== $v = $this->serviceRoleArn) {
            $payload['serviceRoleArn'] = $v;
        }
        if (null !== $v = $this->dynamodbConfig) {
            $payload['dynamodbConfig'] = $v->requestBody();
        }
        if (null !== $v = $this->lambdaConfig) {
            $payload['lambdaConfig'] = $v->requestBody();
        }
        if (null !== $v = $this->elasticsearchConfig) {
            $payload['elasticsearchConfig'] = $v->requestBody();
        }
        if (null !== $v = $this->openSearchServiceConfig) {
            $payload['openSearchServiceConfig'] = $v->requestBody();
        }
        if (null !== $v = $this->httpConfig) {
            $payload['httpConfig'] = $v->requestBody();
        }
        if (null !== $v = $this->relationalDatabaseConfig) {
            $payload['relationalDatabaseConfig'] = $v->requestBody();
        }
        if (null !== $v = $this->eventBridgeConfig) {
            $payload['eventBridgeConfig'] = $v->requestBody();
        }
        if (null !== $v = $this->metricsConfig) {
            if (!DataSourceLevelMetricsConfig::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "metricsConfig" for "%s". The value "%s" is not a valid "DataSourceLevelMetricsConfig".', __CLASS__, $v));
            }
            $payload['metricsConfig'] = $v;
        }

        return $payload;
    }
}
