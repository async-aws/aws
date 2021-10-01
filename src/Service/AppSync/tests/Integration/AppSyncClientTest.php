<?php

namespace AsyncAws\AppSync\Tests\Integration;

use AsyncAws\AppSync\AppSyncClient;
use AsyncAws\AppSync\Input\CreateResolverRequest;
use AsyncAws\AppSync\Input\DeleteResolverRequest;
use AsyncAws\AppSync\Input\GetSchemaCreationStatusRequest;
use AsyncAws\AppSync\Input\ListApiKeysRequest;
use AsyncAws\AppSync\Input\ListResolversRequest;
use AsyncAws\AppSync\Input\StartSchemaCreationRequest;
use AsyncAws\AppSync\Input\UpdateApiKeyRequest;
use AsyncAws\AppSync\Input\UpdateDataSourceRequest;
use AsyncAws\AppSync\Input\UpdateResolverRequest;
use AsyncAws\AppSync\ValueObject\AuthorizationConfig;
use AsyncAws\AppSync\ValueObject\AwsIamConfig;
use AsyncAws\AppSync\ValueObject\CachingConfig;
use AsyncAws\AppSync\ValueObject\DeltaSyncConfig;
use AsyncAws\AppSync\ValueObject\DynamodbDataSourceConfig;
use AsyncAws\AppSync\ValueObject\ElasticsearchDataSourceConfig;
use AsyncAws\AppSync\ValueObject\HttpDataSourceConfig;
use AsyncAws\AppSync\ValueObject\LambdaConflictHandlerConfig;
use AsyncAws\AppSync\ValueObject\LambdaDataSourceConfig;
use AsyncAws\AppSync\ValueObject\PipelineConfig;
use AsyncAws\AppSync\ValueObject\RdsHttpEndpointConfig;
use AsyncAws\AppSync\ValueObject\RelationalDatabaseDataSourceConfig;
use AsyncAws\AppSync\ValueObject\SyncConfig;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;

class AppSyncClientTest extends TestCase
{
    public function testCreateResolver(): void
    {
        $client = $this->getClient();

        $input = new CreateResolverRequest([
            'apiId' => 'change me',
            'typeName' => 'change me',
            'fieldName' => 'change me',
            'dataSourceName' => 'change me',
            'requestMappingTemplate' => 'change me',
            'responseMappingTemplate' => 'change me',
            'kind' => 'change me',
            'pipelineConfig' => new PipelineConfig([
                'functions' => ['change me'],
            ]),
            'syncConfig' => new SyncConfig([
                'conflictHandler' => 'change me',
                'conflictDetection' => 'change me',
                'lambdaConflictHandlerConfig' => new LambdaConflictHandlerConfig([
                    'lambdaConflictHandlerArn' => 'change me',
                ]),
            ]),
            'cachingConfig' => new CachingConfig([
                'ttl' => 1337,
                'cachingKeys' => ['change me'],
            ]),
        ]);
        $result = $client->createResolver($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getresolver());
    }

    public function testDeleteResolver(): void
    {
        $client = $this->getClient();

        $input = new DeleteResolverRequest([
            'apiId' => 'change me',
            'typeName' => 'change me',
            'fieldName' => 'change me',
        ]);
        $result = $client->deleteResolver($input);

        $result->resolve();
    }

    public function testGetSchemaCreationStatus(): void
    {
        $client = $this->getClient();

        $input = new GetSchemaCreationStatusRequest([
            'apiId' => 'change me',
        ]);
        $result = $client->getSchemaCreationStatus($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getstatus());
        self::assertSame('changeIt', $result->getdetails());
    }

    public function testListApiKeys(): void
    {
        $client = $this->getClient();

        $input = new ListApiKeysRequest([
            'apiId' => 'change me',
            'nextToken' => 'change me',
            'maxResults' => 1337,
        ]);
        $result = $client->listApiKeys($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getapiKeys());
        self::assertSame('changeIt', $result->getnextToken());
    }

    public function testListResolvers(): void
    {
        $client = $this->getClient();

        $input = new ListResolversRequest([
            'apiId' => 'change me',
            'typeName' => 'change me',
            'nextToken' => 'change me',
            'maxResults' => 1337,
        ]);
        $result = $client->listResolvers($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getresolvers());
        self::assertSame('changeIt', $result->getnextToken());
    }

    public function testStartSchemaCreation(): void
    {
        $client = $this->getClient();

        $input = new StartSchemaCreationRequest([
            'apiId' => 'change me',
            'definition' => 'change me',
        ]);
        $result = $client->startSchemaCreation($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getstatus());
    }

    public function testUpdateApiKey(): void
    {
        $client = $this->getClient();

        $input = new UpdateApiKeyRequest([
            'apiId' => 'change me',
            'id' => 'change me',
            'description' => 'change me',
            'expires' => 1337,
        ]);
        $result = $client->updateApiKey($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getapiKey());
    }

    public function testUpdateDataSource(): void
    {
        $client = $this->getClient();

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
        $result = $client->updateDataSource($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getdataSource());
    }

    public function testUpdateResolver(): void
    {
        $client = $this->getClient();

        $input = new UpdateResolverRequest([
            'apiId' => 'change me',
            'typeName' => 'change me',
            'fieldName' => 'change me',
            'dataSourceName' => 'change me',
            'requestMappingTemplate' => 'change me',
            'responseMappingTemplate' => 'change me',
            'kind' => 'change me',
            'pipelineConfig' => new PipelineConfig([
                'functions' => ['change me'],
            ]),
            'syncConfig' => new SyncConfig([
                'conflictHandler' => 'change me',
                'conflictDetection' => 'change me',
                'lambdaConflictHandlerConfig' => new LambdaConflictHandlerConfig([
                    'lambdaConflictHandlerArn' => 'change me',
                ]),
            ]),
            'cachingConfig' => new CachingConfig([
                'ttl' => 1337,
                'cachingKeys' => ['change me'],
            ]),
        ]);
        $result = $client->updateResolver($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getresolver());
    }

    private function getClient(): AppSyncClient
    {
        self::markTestSkipped('There is no docker image available for AppSync.');

        return new AppSyncClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
