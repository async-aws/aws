<?php

namespace AsyncAws\AppSync\Tests\Unit\Input;

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
        self::fail('Not implemented');

        $input = new UpdateDataSourceRequest([
            'apiId' => 'change me',
            'name' => 'change me',
            'description' => 'change me',
            'type' => 'change me',
            'serviceRoleArn' => 'change me',
            'dynamodbConfig' => new DynamodbDataSourceConfig([
                'tableName' => 'change me',
                'awsRegion' => 'change me',
                'useCallerCredentials' => false,
                'deltaSyncConfig' => new DeltaSyncConfig([
                    'baseTableTTL' => 1337,
                    'deltaSyncTableName' => 'change me',
                    'deltaSyncTableTTL' => 1337,
                ]),
                'versioned' => false,
            ]),
            'lambdaConfig' => new LambdaDataSourceConfig([
                'lambdaFunctionArn' => 'change me',
            ]),
            'elasticsearchConfig' => new ElasticsearchDataSourceConfig([
                'endpoint' => 'change me',
                'awsRegion' => 'change me',
            ]),
            'httpConfig' => new HttpDataSourceConfig([
                'endpoint' => 'change me',
                'authorizationConfig' => new AuthorizationConfig([
                    'authorizationType' => 'change me',
                    'awsIamConfig' => new AwsIamConfig([
                        'signingRegion' => 'change me',
                        'signingServiceName' => 'change me',
                    ]),
                ]),
            ]),
            'relationalDatabaseConfig' => new RelationalDatabaseDataSourceConfig([
                'relationalDatabaseSourceType' => 'change me',
                'rdsHttpEndpointConfig' => new RdsHttpEndpointConfig([
                    'awsRegion' => 'change me',
                    'dbClusterIdentifier' => 'change me',
                    'databaseName' => 'change me',
                    'schema' => 'change me',
                    'awsSecretStoreArn' => 'change me',
                ]),
            ]),
        ]);

        // see https://docs.aws.amazon.com/appsync/latest/APIReference/API_UpdateDataSource.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/json

            {
            "change": "it"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
