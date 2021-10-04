<?php

namespace AsyncAws\AppSync\Result;

use AsyncAws\AppSync\ValueObject\AuthorizationConfig;
use AsyncAws\AppSync\ValueObject\AwsIamConfig;
use AsyncAws\AppSync\ValueObject\DataSource;
use AsyncAws\AppSync\ValueObject\DeltaSyncConfig;
use AsyncAws\AppSync\ValueObject\DynamodbDataSourceConfig;
use AsyncAws\AppSync\ValueObject\ElasticsearchDataSourceConfig;
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

        $this->dataSource = empty($data['dataSource']) ? null : new DataSource([
            'dataSourceArn' => isset($data['dataSource']['dataSourceArn']) ? (string) $data['dataSource']['dataSourceArn'] : null,
            'name' => isset($data['dataSource']['name']) ? (string) $data['dataSource']['name'] : null,
            'description' => isset($data['dataSource']['description']) ? (string) $data['dataSource']['description'] : null,
            'type' => isset($data['dataSource']['type']) ? (string) $data['dataSource']['type'] : null,
            'serviceRoleArn' => isset($data['dataSource']['serviceRoleArn']) ? (string) $data['dataSource']['serviceRoleArn'] : null,
            'dynamodbConfig' => empty($data['dataSource']['dynamodbConfig']) ? null : new DynamodbDataSourceConfig([
                'tableName' => (string) $data['dataSource']['dynamodbConfig']['tableName'],
                'awsRegion' => (string) $data['dataSource']['dynamodbConfig']['awsRegion'],
                'useCallerCredentials' => isset($data['dataSource']['dynamodbConfig']['useCallerCredentials']) ? filter_var($data['dataSource']['dynamodbConfig']['useCallerCredentials'], \FILTER_VALIDATE_BOOLEAN) : null,
                'deltaSyncConfig' => empty($data['dataSource']['dynamodbConfig']['deltaSyncConfig']) ? null : new DeltaSyncConfig([
                    'baseTableTTL' => isset($data['dataSource']['dynamodbConfig']['deltaSyncConfig']['baseTableTTL']) ? (string) $data['dataSource']['dynamodbConfig']['deltaSyncConfig']['baseTableTTL'] : null,
                    'deltaSyncTableName' => isset($data['dataSource']['dynamodbConfig']['deltaSyncConfig']['deltaSyncTableName']) ? (string) $data['dataSource']['dynamodbConfig']['deltaSyncConfig']['deltaSyncTableName'] : null,
                    'deltaSyncTableTTL' => isset($data['dataSource']['dynamodbConfig']['deltaSyncConfig']['deltaSyncTableTTL']) ? (string) $data['dataSource']['dynamodbConfig']['deltaSyncConfig']['deltaSyncTableTTL'] : null,
                ]),
                'versioned' => isset($data['dataSource']['dynamodbConfig']['versioned']) ? filter_var($data['dataSource']['dynamodbConfig']['versioned'], \FILTER_VALIDATE_BOOLEAN) : null,
            ]),
            'lambdaConfig' => empty($data['dataSource']['lambdaConfig']) ? null : new LambdaDataSourceConfig([
                'lambdaFunctionArn' => (string) $data['dataSource']['lambdaConfig']['lambdaFunctionArn'],
            ]),
            'elasticsearchConfig' => empty($data['dataSource']['elasticsearchConfig']) ? null : new ElasticsearchDataSourceConfig([
                'endpoint' => (string) $data['dataSource']['elasticsearchConfig']['endpoint'],
                'awsRegion' => (string) $data['dataSource']['elasticsearchConfig']['awsRegion'],
            ]),
            'openSearchServiceConfig' => empty($data['dataSource']['openSearchServiceConfig']) ? null : new OpenSearchServiceDataSourceConfig([
                'endpoint' => (string) $data['dataSource']['openSearchServiceConfig']['endpoint'],
                'awsRegion' => (string) $data['dataSource']['openSearchServiceConfig']['awsRegion'],
            ]),
            'httpConfig' => empty($data['dataSource']['httpConfig']) ? null : new HttpDataSourceConfig([
                'endpoint' => isset($data['dataSource']['httpConfig']['endpoint']) ? (string) $data['dataSource']['httpConfig']['endpoint'] : null,
                'authorizationConfig' => empty($data['dataSource']['httpConfig']['authorizationConfig']) ? null : new AuthorizationConfig([
                    'authorizationType' => (string) $data['dataSource']['httpConfig']['authorizationConfig']['authorizationType'],
                    'awsIamConfig' => empty($data['dataSource']['httpConfig']['authorizationConfig']['awsIamConfig']) ? null : new AwsIamConfig([
                        'signingRegion' => isset($data['dataSource']['httpConfig']['authorizationConfig']['awsIamConfig']['signingRegion']) ? (string) $data['dataSource']['httpConfig']['authorizationConfig']['awsIamConfig']['signingRegion'] : null,
                        'signingServiceName' => isset($data['dataSource']['httpConfig']['authorizationConfig']['awsIamConfig']['signingServiceName']) ? (string) $data['dataSource']['httpConfig']['authorizationConfig']['awsIamConfig']['signingServiceName'] : null,
                    ]),
                ]),
            ]),
            'relationalDatabaseConfig' => empty($data['dataSource']['relationalDatabaseConfig']) ? null : new RelationalDatabaseDataSourceConfig([
                'relationalDatabaseSourceType' => isset($data['dataSource']['relationalDatabaseConfig']['relationalDatabaseSourceType']) ? (string) $data['dataSource']['relationalDatabaseConfig']['relationalDatabaseSourceType'] : null,
                'rdsHttpEndpointConfig' => empty($data['dataSource']['relationalDatabaseConfig']['rdsHttpEndpointConfig']) ? null : new RdsHttpEndpointConfig([
                    'awsRegion' => isset($data['dataSource']['relationalDatabaseConfig']['rdsHttpEndpointConfig']['awsRegion']) ? (string) $data['dataSource']['relationalDatabaseConfig']['rdsHttpEndpointConfig']['awsRegion'] : null,
                    'dbClusterIdentifier' => isset($data['dataSource']['relationalDatabaseConfig']['rdsHttpEndpointConfig']['dbClusterIdentifier']) ? (string) $data['dataSource']['relationalDatabaseConfig']['rdsHttpEndpointConfig']['dbClusterIdentifier'] : null,
                    'databaseName' => isset($data['dataSource']['relationalDatabaseConfig']['rdsHttpEndpointConfig']['databaseName']) ? (string) $data['dataSource']['relationalDatabaseConfig']['rdsHttpEndpointConfig']['databaseName'] : null,
                    'schema' => isset($data['dataSource']['relationalDatabaseConfig']['rdsHttpEndpointConfig']['schema']) ? (string) $data['dataSource']['relationalDatabaseConfig']['rdsHttpEndpointConfig']['schema'] : null,
                    'awsSecretStoreArn' => isset($data['dataSource']['relationalDatabaseConfig']['rdsHttpEndpointConfig']['awsSecretStoreArn']) ? (string) $data['dataSource']['relationalDatabaseConfig']['rdsHttpEndpointConfig']['awsSecretStoreArn'] : null,
                ]),
            ]),
        ]);
    }
}
