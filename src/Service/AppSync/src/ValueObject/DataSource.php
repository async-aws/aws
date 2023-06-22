<?php

namespace AsyncAws\AppSync\ValueObject;

use AsyncAws\AppSync\Enum\DataSourceType;

/**
 * Describes a data source.
 */
final class DataSource
{
    /**
     * The data source Amazon Resource Name (ARN).
     */
    private $dataSourceArn;

    /**
     * The name of the data source.
     */
    private $name;

    /**
     * The description of the data source.
     */
    private $description;

    /**
     * The type of the data source.
     *
     * - **AWS_LAMBDA**: The data source is an Lambda function.
     * - **AMAZON_DYNAMODB**: The data source is an Amazon DynamoDB table.
     * - **AMAZON_ELASTICSEARCH**: The data source is an Amazon OpenSearch Service domain.
     * - **AMAZON_OPENSEARCH_SERVICE**: The data source is an Amazon OpenSearch Service domain.
     * - **AMAZON_EVENTBRIDGE**: The data source is an Amazon EventBridge configuration.
     * - **NONE**: There is no data source. Use this type when you want to invoke a GraphQL operation without connecting to
     *   a data source, such as when you're performing data transformation with resolvers or invoking a subscription from a
     *   mutation.
     * - **HTTP**: The data source is an HTTP endpoint.
     * - **RELATIONAL_DATABASE**: The data source is a relational database.
     */
    private $type;

    /**
     * The Identity and Access Management (IAM) service role Amazon Resource Name (ARN) for the data source. The system
     * assumes this role when accessing the data source.
     */
    private $serviceRoleArn;

    /**
     * DynamoDB settings.
     */
    private $dynamodbConfig;

    /**
     * Lambda settings.
     */
    private $lambdaConfig;

    /**
     * Amazon OpenSearch Service settings.
     */
    private $elasticsearchConfig;

    /**
     * Amazon OpenSearch Service settings.
     */
    private $openSearchServiceConfig;

    /**
     * HTTP endpoint settings.
     */
    private $httpConfig;

    /**
     * Relational database settings.
     */
    private $relationalDatabaseConfig;

    /**
     * Amazon EventBridge settings.
     */
    private $eventBridgeConfig;

    /**
     * @param array{
     *   dataSourceArn?: null|string,
     *   name?: null|string,
     *   description?: null|string,
     *   type?: null|DataSourceType::*,
     *   serviceRoleArn?: null|string,
     *   dynamodbConfig?: null|DynamodbDataSourceConfig|array,
     *   lambdaConfig?: null|LambdaDataSourceConfig|array,
     *   elasticsearchConfig?: null|ElasticsearchDataSourceConfig|array,
     *   openSearchServiceConfig?: null|OpenSearchServiceDataSourceConfig|array,
     *   httpConfig?: null|HttpDataSourceConfig|array,
     *   relationalDatabaseConfig?: null|RelationalDatabaseDataSourceConfig|array,
     *   eventBridgeConfig?: null|EventBridgeDataSourceConfig|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->dataSourceArn = $input['dataSourceArn'] ?? null;
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
    }

    /**
     * @param array{
     *   dataSourceArn?: null|string,
     *   name?: null|string,
     *   description?: null|string,
     *   type?: null|DataSourceType::*,
     *   serviceRoleArn?: null|string,
     *   dynamodbConfig?: null|DynamodbDataSourceConfig|array,
     *   lambdaConfig?: null|LambdaDataSourceConfig|array,
     *   elasticsearchConfig?: null|ElasticsearchDataSourceConfig|array,
     *   openSearchServiceConfig?: null|OpenSearchServiceDataSourceConfig|array,
     *   httpConfig?: null|HttpDataSourceConfig|array,
     *   relationalDatabaseConfig?: null|RelationalDatabaseDataSourceConfig|array,
     *   eventBridgeConfig?: null|EventBridgeDataSourceConfig|array,
     * }|DataSource $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDataSourceArn(): ?string
    {
        return $this->dataSourceArn;
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
}
