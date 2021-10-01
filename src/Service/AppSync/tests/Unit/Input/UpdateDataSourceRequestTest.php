<?php

namespace AsyncAws\AppSync\Tests\Unit\Input;

use AsyncAws\AppSync\Enum\AuthorizationType;
use AsyncAws\AppSync\Enum\DataSourceType;
use AsyncAws\AppSync\Enum\RelationalDatabaseSourceType;
use AsyncAws\AppSync\Input\UpdateDataSourceRequest;
use AsyncAws\AppSync\ValueObject\AuthorizationConfig;
use AsyncAws\AppSync\ValueObject\AwsIamConfig;
use AsyncAws\AppSync\ValueObject\DeltaSyncConfig;
use AsyncAws\AppSync\ValueObject\DynamodbDataSourceConfig;
use AsyncAws\AppSync\ValueObject\ElasticsearchDataSourceConfig;
use AsyncAws\AppSync\ValueObject\HttpDataSourceConfig;
use AsyncAws\AppSync\ValueObject\LambdaDataSourceConfig;
use AsyncAws\AppSync\ValueObject\RdsHttpEndpointConfig;
use AsyncAws\AppSync\ValueObject\RelationalDatabaseDataSourceConfig;
use AsyncAws\Core\Test\TestCase;

class UpdateDataSourceRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new UpdateDataSourceRequest([
            'apiId' => 'api123',
            'name' => 'dataSourceName',
            'description' => 'This is a description',
            'type' => DataSourceType::AWS_LAMBDA,
            'serviceRoleArn' => 'aws::service-role',
            'dynamodbConfig' => new DynamodbDataSourceConfig([
                'tableName' => 'dynamoTable',
                'awsRegion' => 'world-1',
                'useCallerCredentials' => false,
                'deltaSyncConfig' => new DeltaSyncConfig([
                    'baseTableTTL' => 1337,
                    'deltaSyncTableName' => 'deltaSyncTable',
                    'deltaSyncTableTTL' => 42,
                ]),
                'versioned' => false,
            ]),
            'lambdaConfig' => new LambdaDataSourceConfig([
                'lambdaFunctionArn' => 'aws::lambda-function',
            ]),
            'elasticsearchConfig' => new ElasticsearchDataSourceConfig([
                'endpoint' => 'esEndpoint',
                'awsRegion' => 'world-1',
            ]),
            'httpConfig' => new HttpDataSourceConfig([
                'endpoint' => 'http://foo.bar',
                'authorizationConfig' => new AuthorizationConfig([
                    'authorizationType' => AuthorizationType::AWS_IAM,
                    'awsIamConfig' => new AwsIamConfig([
                        'signingRegion' => 'world-1',
                        'signingServiceName' => 'signingService',
                    ]),
                ]),
            ]),
            'relationalDatabaseConfig' => new RelationalDatabaseDataSourceConfig([
                'relationalDatabaseSourceType' => RelationalDatabaseSourceType::RDS_HTTP_ENDPOINT,
                'rdsHttpEndpointConfig' => new RdsHttpEndpointConfig([
                    'awsRegion' => 'world-1',
                    'dbClusterIdentifier' => 'dbCluster',
                    'databaseName' => 'dbName',
                    'schema' => 'dbSchema',
                    'awsSecretStoreArn' => 'aws::secretStore',
                ]),
            ]),
        ]);

        // see https://docs.aws.amazon.com/appsync/latest/APIReference/API_UpdateDataSource.html
        $expected = '
            POST /v1/apis/api123/datasources/dataSourceName HTTP/1.1
            Content-type: application/json

            {
               "description": "This is a description",
               "dynamodbConfig": {
                  "awsRegion": "world-1",
                  "deltaSyncConfig": {
                     "baseTableTTL": 1337,
                     "deltaSyncTableName": "deltaSyncTable",
                     "deltaSyncTableTTL": 42
                  },
                  "tableName": "dynamoTable",
                  "useCallerCredentials": false,
                  "versioned": false
               },
               "elasticsearchConfig": {
                  "awsRegion": "world-1",
                  "endpoint": "esEndpoint"
               },
               "httpConfig": {
                  "authorizationConfig": {
                     "authorizationType": "AWS_IAM",
                     "awsIamConfig": {
                        "signingRegion": "world-1",
                        "signingServiceName": "signingService"
                     }
                  },
                  "endpoint": "http://foo.bar"
               },
               "lambdaConfig": {
                  "lambdaFunctionArn": "aws::lambda-function"
               },
               "relationalDatabaseConfig": {
                  "rdsHttpEndpointConfig": {
                     "awsRegion": "world-1",
                     "awsSecretStoreArn": "aws::secretStore",
                     "databaseName": "dbName",
                     "dbClusterIdentifier": "dbCluster",
                     "schema": "dbSchema"
                  },
                  "relationalDatabaseSourceType": "RDS_HTTP_ENDPOINT"
               },
               "serviceRoleArn": "aws::service-role",
               "type": "AWS_LAMBDA"
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
