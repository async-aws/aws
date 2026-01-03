<?php

namespace AsyncAws\AppSync\Result;

use AsyncAws\AppSync\Enum\AuthorizationType;
use AsyncAws\AppSync\Enum\DataSourceLevelMetricsConfig;
use AsyncAws\AppSync\Enum\DataSourceType;
use AsyncAws\AppSync\Enum\RelationalDatabaseSourceType;
use AsyncAws\AppSync\ValueObject\AuthorizationConfig;
use AsyncAws\AppSync\ValueObject\AwsIamConfig;
use AsyncAws\AppSync\ValueObject\DataSource;
use AsyncAws\AppSync\ValueObject\DeltaSyncConfig;
use AsyncAws\AppSync\ValueObject\DynamodbDataSourceConfig;
use AsyncAws\AppSync\ValueObject\ElasticsearchDataSourceConfig;
use AsyncAws\AppSync\ValueObject\EventBridgeDataSourceConfig;
use AsyncAws\AppSync\ValueObject\HttpDataSourceConfig;
use AsyncAws\AppSync\ValueObject\LambdaDataSourceConfig;
use AsyncAws\AppSync\ValueObject\OpenSearchServiceDataSourceConfig;
use AsyncAws\AppSync\ValueObject\RdsHttpEndpointConfig;
use AsyncAws\AppSync\ValueObject\RelationalDatabaseDataSourceConfig;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class UpdateDataSourceResponse extends Result
{
    /**
     * The updated `DataSource` object.
     *
     * @var DataSource|null
     */
    private $dataSource;

    public function getDataSource(): ?DataSource
    {
        $this->initialize();

        return $this->dataSource;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->dataSource = empty($data['dataSource']) ? null : $this->populateResultDataSource($data['dataSource']);
    }

    private function populateResultAuthorizationConfig(array $json): AuthorizationConfig
    {
        return new AuthorizationConfig([
            'authorizationType' => !AuthorizationType::exists((string) $json['authorizationType']) ? AuthorizationType::UNKNOWN_TO_SDK : (string) $json['authorizationType'],
            'awsIamConfig' => empty($json['awsIamConfig']) ? null : $this->populateResultAwsIamConfig($json['awsIamConfig']),
        ]);
    }

    private function populateResultAwsIamConfig(array $json): AwsIamConfig
    {
        return new AwsIamConfig([
            'signingRegion' => isset($json['signingRegion']) ? (string) $json['signingRegion'] : null,
            'signingServiceName' => isset($json['signingServiceName']) ? (string) $json['signingServiceName'] : null,
        ]);
    }

    private function populateResultDataSource(array $json): DataSource
    {
        return new DataSource([
            'dataSourceArn' => isset($json['dataSourceArn']) ? (string) $json['dataSourceArn'] : null,
            'name' => isset($json['name']) ? (string) $json['name'] : null,
            'description' => isset($json['description']) ? (string) $json['description'] : null,
            'type' => isset($json['type']) ? (!DataSourceType::exists((string) $json['type']) ? DataSourceType::UNKNOWN_TO_SDK : (string) $json['type']) : null,
            'serviceRoleArn' => isset($json['serviceRoleArn']) ? (string) $json['serviceRoleArn'] : null,
            'dynamodbConfig' => empty($json['dynamodbConfig']) ? null : $this->populateResultDynamodbDataSourceConfig($json['dynamodbConfig']),
            'lambdaConfig' => empty($json['lambdaConfig']) ? null : $this->populateResultLambdaDataSourceConfig($json['lambdaConfig']),
            'elasticsearchConfig' => empty($json['elasticsearchConfig']) ? null : $this->populateResultElasticsearchDataSourceConfig($json['elasticsearchConfig']),
            'openSearchServiceConfig' => empty($json['openSearchServiceConfig']) ? null : $this->populateResultOpenSearchServiceDataSourceConfig($json['openSearchServiceConfig']),
            'httpConfig' => empty($json['httpConfig']) ? null : $this->populateResultHttpDataSourceConfig($json['httpConfig']),
            'relationalDatabaseConfig' => empty($json['relationalDatabaseConfig']) ? null : $this->populateResultRelationalDatabaseDataSourceConfig($json['relationalDatabaseConfig']),
            'eventBridgeConfig' => empty($json['eventBridgeConfig']) ? null : $this->populateResultEventBridgeDataSourceConfig($json['eventBridgeConfig']),
            'metricsConfig' => isset($json['metricsConfig']) ? (!DataSourceLevelMetricsConfig::exists((string) $json['metricsConfig']) ? DataSourceLevelMetricsConfig::UNKNOWN_TO_SDK : (string) $json['metricsConfig']) : null,
        ]);
    }

    private function populateResultDeltaSyncConfig(array $json): DeltaSyncConfig
    {
        return new DeltaSyncConfig([
            'baseTableTTL' => isset($json['baseTableTTL']) ? (int) $json['baseTableTTL'] : null,
            'deltaSyncTableName' => isset($json['deltaSyncTableName']) ? (string) $json['deltaSyncTableName'] : null,
            'deltaSyncTableTTL' => isset($json['deltaSyncTableTTL']) ? (int) $json['deltaSyncTableTTL'] : null,
        ]);
    }

    private function populateResultDynamodbDataSourceConfig(array $json): DynamodbDataSourceConfig
    {
        return new DynamodbDataSourceConfig([
            'tableName' => (string) $json['tableName'],
            'awsRegion' => (string) $json['awsRegion'],
            'useCallerCredentials' => isset($json['useCallerCredentials']) ? filter_var($json['useCallerCredentials'], \FILTER_VALIDATE_BOOLEAN) : null,
            'deltaSyncConfig' => empty($json['deltaSyncConfig']) ? null : $this->populateResultDeltaSyncConfig($json['deltaSyncConfig']),
            'versioned' => isset($json['versioned']) ? filter_var($json['versioned'], \FILTER_VALIDATE_BOOLEAN) : null,
        ]);
    }

    private function populateResultElasticsearchDataSourceConfig(array $json): ElasticsearchDataSourceConfig
    {
        return new ElasticsearchDataSourceConfig([
            'endpoint' => (string) $json['endpoint'],
            'awsRegion' => (string) $json['awsRegion'],
        ]);
    }

    private function populateResultEventBridgeDataSourceConfig(array $json): EventBridgeDataSourceConfig
    {
        return new EventBridgeDataSourceConfig([
            'eventBusArn' => (string) $json['eventBusArn'],
        ]);
    }

    private function populateResultHttpDataSourceConfig(array $json): HttpDataSourceConfig
    {
        return new HttpDataSourceConfig([
            'endpoint' => isset($json['endpoint']) ? (string) $json['endpoint'] : null,
            'authorizationConfig' => empty($json['authorizationConfig']) ? null : $this->populateResultAuthorizationConfig($json['authorizationConfig']),
        ]);
    }

    private function populateResultLambdaDataSourceConfig(array $json): LambdaDataSourceConfig
    {
        return new LambdaDataSourceConfig([
            'lambdaFunctionArn' => (string) $json['lambdaFunctionArn'],
        ]);
    }

    private function populateResultOpenSearchServiceDataSourceConfig(array $json): OpenSearchServiceDataSourceConfig
    {
        return new OpenSearchServiceDataSourceConfig([
            'endpoint' => (string) $json['endpoint'],
            'awsRegion' => (string) $json['awsRegion'],
        ]);
    }

    private function populateResultRdsHttpEndpointConfig(array $json): RdsHttpEndpointConfig
    {
        return new RdsHttpEndpointConfig([
            'awsRegion' => isset($json['awsRegion']) ? (string) $json['awsRegion'] : null,
            'dbClusterIdentifier' => isset($json['dbClusterIdentifier']) ? (string) $json['dbClusterIdentifier'] : null,
            'databaseName' => isset($json['databaseName']) ? (string) $json['databaseName'] : null,
            'schema' => isset($json['schema']) ? (string) $json['schema'] : null,
            'awsSecretStoreArn' => isset($json['awsSecretStoreArn']) ? (string) $json['awsSecretStoreArn'] : null,
        ]);
    }

    private function populateResultRelationalDatabaseDataSourceConfig(array $json): RelationalDatabaseDataSourceConfig
    {
        return new RelationalDatabaseDataSourceConfig([
            'relationalDatabaseSourceType' => isset($json['relationalDatabaseSourceType']) ? (!RelationalDatabaseSourceType::exists((string) $json['relationalDatabaseSourceType']) ? RelationalDatabaseSourceType::UNKNOWN_TO_SDK : (string) $json['relationalDatabaseSourceType']) : null,
            'rdsHttpEndpointConfig' => empty($json['rdsHttpEndpointConfig']) ? null : $this->populateResultRdsHttpEndpointConfig($json['rdsHttpEndpointConfig']),
        ]);
    }
}
