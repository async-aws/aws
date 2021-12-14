<?php

namespace AsyncAws\AppSync\ValueObject;

use AsyncAws\AppSync\Enum\DataSourceType;

/**
 * The updated `DataSource` object.
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
    }

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
