<?php

namespace AsyncAws\AppSync\Tests\Unit\Result;

use AsyncAws\AppSync\Enum\AuthorizationType;
use AsyncAws\AppSync\Enum\DataSourceType;
use AsyncAws\AppSync\Enum\RelationalDatabaseSourceType;
use AsyncAws\AppSync\Result\UpdateDataSourceResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class UpdateDataSourceResponseTest extends TestCase
{
    public function testUpdateDataSourceResponse(): void
    {
        // see https://docs.aws.amazon.com/appsync/latest/APIReference/API_UpdateDataSource.html
        $response = new SimpleMockedResponse('{
           "dataSource": {
              "dataSourceArn": "aws::dataSource",
              "description": "Description here",
              "dynamodbConfig": {
                 "awsRegion": "world-1",
                 "deltaSyncConfig": {
                    "baseTableTTL": 1337,
                    "deltaSyncTableName": "syncTable",
                    "deltaSyncTableTTL": 42
                 },
                 "tableName": "dbTable",
                 "useCallerCredentials": true,
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
                 "endpoint": "authEndpoint"
              },
              "lambdaConfig": {
                 "lambdaFunctionArn": "aws::lambda"
              },
              "name": "source",
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
              "serviceRoleArn": "aws::serviceRole",
              "type": "AWS_LAMBDA"
           }
        }');

        $client = new MockHttpClient($response);
        $result = new UpdateDataSourceResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        $dataSource = $result->getDataSource();
        self::assertNotNull($dataSource);

        self::assertEquals('aws::dataSource', $dataSource->getDataSourceArn());
        self::assertEquals('Description here', $dataSource->getDescription());
        self::assertEquals('source', $dataSource->getName());
        self::assertEquals('aws::serviceRole', $dataSource->getServiceRoleArn());
        self::assertEquals(DataSourceType::AWS_LAMBDA, $dataSource->getType());

        $dynamoConfig = $dataSource->getDynamodbConfig();
        self::assertNotNull($dynamoConfig);
        self::assertEquals('world-1', $dynamoConfig->getAwsRegion());
        self::assertEquals('dbTable', $dynamoConfig->getTableName());
        self::assertEquals(true, $dynamoConfig->getUseCallerCredentials());
        self::assertEquals(false, $dynamoConfig->getVersioned());
        self::assertNotNull($dynamoConfig->getDeltaSyncConfig());
        self::assertEquals(1337, $dynamoConfig->getDeltaSyncConfig()->getBaseTableTtl());
        self::assertEquals(42, $dynamoConfig->getDeltaSyncConfig()->getDeltaSyncTableTtl());
        self::assertEquals('syncTable', $dynamoConfig->getDeltaSyncConfig()->getDeltaSyncTableName());

        $esConfig = $dataSource->getElasticsearchConfig();
        self::assertNotNull($esConfig);
        self::assertEquals('world-1', $esConfig->getAwsRegion());
        self::assertEquals('esEndpoint', $esConfig->getEndpoint());

        $httpConfig = $dataSource->getHttpConfig();
        self::assertNotNull($httpConfig);
        self::assertEquals('authEndpoint', $httpConfig->getEndpoint());
        self::assertNotNull($httpConfig->getAuthorizationConfig());
        self::assertEquals(AuthorizationType::AWS_IAM, $httpConfig->getAuthorizationConfig()->getAuthorizationType());
        self::assertNotNull($httpConfig->getAuthorizationConfig()->getAwsIamConfig());
        self::assertEquals('world-1', $httpConfig->getAuthorizationConfig()->getAwsIamConfig()->getSigningRegion());
        self::assertEquals('signingService', $httpConfig->getAuthorizationConfig()->getAwsIamConfig()->getSigningServiceName());

        $lambdaConfig = $dataSource->getLambdaConfig();
        self::assertNotNull($lambdaConfig);
        self::assertEquals('aws::lambda', $lambdaConfig->getLambdaFunctionArn());

        $rdsConfig = $dataSource->getRelationalDatabaseConfig();
        self::assertNotNull($rdsConfig);
        self::assertEquals(RelationalDatabaseSourceType::RDS_HTTP_ENDPOINT, $rdsConfig->getRelationalDatabaseSourceType());
        self::assertNotNull($rdsConfig->getRdsHttpEndpointConfig());
        self::assertEquals('world-1', $rdsConfig->getRdsHttpEndpointConfig()->getAwsRegion());
        self::assertEquals('aws::secretStore', $rdsConfig->getRdsHttpEndpointConfig()->getAwsSecretStoreArn());
        self::assertEquals('dbName', $rdsConfig->getRdsHttpEndpointConfig()->getDatabaseName());
        self::assertEquals('dbCluster', $rdsConfig->getRdsHttpEndpointConfig()->getDbClusterIdentifier());
        self::assertEquals('dbSchema', $rdsConfig->getRdsHttpEndpointConfig()->getSchema());
    }
}
