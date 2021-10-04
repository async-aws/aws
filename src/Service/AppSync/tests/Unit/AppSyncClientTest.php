<?php

namespace AsyncAws\AppSync\Tests\Unit;

use AsyncAws\AppSync\AppSyncClient;
use AsyncAws\AppSync\Enum\DataSourceType;
use AsyncAws\AppSync\Input\CreateResolverRequest;
use AsyncAws\AppSync\Input\DeleteResolverRequest;
use AsyncAws\AppSync\Input\GetSchemaCreationStatusRequest;
use AsyncAws\AppSync\Input\ListApiKeysRequest;
use AsyncAws\AppSync\Input\ListResolversRequest;
use AsyncAws\AppSync\Input\StartSchemaCreationRequest;
use AsyncAws\AppSync\Input\UpdateApiKeyRequest;
use AsyncAws\AppSync\Input\UpdateDataSourceRequest;
use AsyncAws\AppSync\Input\UpdateResolverRequest;
use AsyncAws\AppSync\Result\CreateResolverResponse;
use AsyncAws\AppSync\Result\DeleteResolverResponse;
use AsyncAws\AppSync\Result\GetSchemaCreationStatusResponse;
use AsyncAws\AppSync\Result\ListApiKeysResponse;
use AsyncAws\AppSync\Result\ListResolversResponse;
use AsyncAws\AppSync\Result\StartSchemaCreationResponse;
use AsyncAws\AppSync\Result\UpdateApiKeyResponse;
use AsyncAws\AppSync\Result\UpdateDataSourceResponse;
use AsyncAws\AppSync\Result\UpdateResolverResponse;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class AppSyncClientTest extends TestCase
{
    public function testCreateResolver(): void
    {
        $client = new AppSyncClient([], new NullProvider(), new MockHttpClient());

        $input = new CreateResolverRequest([
            'apiId' => 'api123',
            'typeName' => 'type',
            'fieldName' => 'field',
        ]);
        $result = $client->createResolver($input);

        self::assertInstanceOf(CreateResolverResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDeleteResolver(): void
    {
        $client = new AppSyncClient([], new NullProvider(), new MockHttpClient());

        $input = new DeleteResolverRequest([
            'apiId' => 'api123',
            'typeName' => 'type',
            'fieldName' => 'field',
        ]);
        $result = $client->deleteResolver($input);

        self::assertInstanceOf(DeleteResolverResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetSchemaCreationStatus(): void
    {
        $client = new AppSyncClient([], new NullProvider(), new MockHttpClient());

        $input = new GetSchemaCreationStatusRequest([
            'apiId' => 'api123',
        ]);
        $result = $client->getSchemaCreationStatus($input);

        self::assertInstanceOf(GetSchemaCreationStatusResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListApiKeys(): void
    {
        $client = new AppSyncClient([], new NullProvider(), new MockHttpClient());

        $input = new ListApiKeysRequest([
            'apiId' => 'api123',

        ]);
        $result = $client->listApiKeys($input);

        self::assertInstanceOf(ListApiKeysResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListResolvers(): void
    {
        $client = new AppSyncClient([], new NullProvider(), new MockHttpClient());

        $input = new ListResolversRequest([
            'apiId' => 'api123',
            'typeName' => 'type',
        ]);
        $result = $client->listResolvers($input);

        self::assertInstanceOf(ListResolversResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testStartSchemaCreation(): void
    {
        $client = new AppSyncClient([], new NullProvider(), new MockHttpClient());

        $input = new StartSchemaCreationRequest([
            'apiId' => 'api123',
            'definition' => 'schemaDefinition',
        ]);
        $result = $client->startSchemaCreation($input);

        self::assertInstanceOf(StartSchemaCreationResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testUpdateApiKey(): void
    {
        $client = new AppSyncClient([], new NullProvider(), new MockHttpClient());

        $input = new UpdateApiKeyRequest([
            'apiId' => 'api123',
            'id' => 'keyId',
        ]);
        $result = $client->updateApiKey($input);

        self::assertInstanceOf(UpdateApiKeyResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testUpdateDataSource(): void
    {
        $client = new AppSyncClient([], new NullProvider(), new MockHttpClient());

        $input = new UpdateDataSourceRequest([
            'apiId' => 'api123',
            'name' => 'dataSource',
            'type' => DataSourceType::AMAZON_DYNAMODB,
        ]);
        $result = $client->updateDataSource($input);

        self::assertInstanceOf(UpdateDataSourceResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testUpdateResolver(): void
    {
        $client = new AppSyncClient([], new NullProvider(), new MockHttpClient());

        $input = new UpdateResolverRequest([
            'apiId' => 'api123',
            'typeName' => 'type',
            'fieldName' => 'field',
        ]);
        $result = $client->updateResolver($input);

        self::assertInstanceOf(UpdateResolverResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
