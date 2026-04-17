<?php

namespace AsyncAws\Lambda\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Result;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Input\AddLayerVersionPermissionRequest;
use AsyncAws\Lambda\Input\DeleteFunctionRequest;
use AsyncAws\Lambda\Input\GetAliasRequest;
use AsyncAws\Lambda\Input\GetFunctionConfigurationRequest;
use AsyncAws\Lambda\Input\InvocationRequest;
use AsyncAws\Lambda\Input\ListAliasesRequest;
use AsyncAws\Lambda\Input\ListEventSourceMappingsRequest;
use AsyncAws\Lambda\Input\ListFunctionsRequest;
use AsyncAws\Lambda\Input\ListLayerVersionsRequest;
use AsyncAws\Lambda\Input\ListVersionsByFunctionRequest;
use AsyncAws\Lambda\Input\PublishLayerVersionRequest;
use AsyncAws\Lambda\Input\PutFunctionConcurrencyRequest;
use AsyncAws\Lambda\Input\UpdateFunctionConfigurationRequest;
use AsyncAws\Lambda\LambdaClient;
use AsyncAws\Lambda\Result\AddLayerVersionPermissionResponse;
use AsyncAws\Lambda\Result\AliasConfiguration;
use AsyncAws\Lambda\Result\Concurrency;
use AsyncAws\Lambda\Result\FunctionConfiguration;
use AsyncAws\Lambda\Result\InvocationResponse;
use AsyncAws\Lambda\Result\ListAliasesResponse;
use AsyncAws\Lambda\Result\ListEventSourceMappingsResponse;
use AsyncAws\Lambda\Result\ListFunctionsResponse;
use AsyncAws\Lambda\Result\ListLayerVersionsResponse;
use AsyncAws\Lambda\Result\ListVersionsByFunctionResponse;
use AsyncAws\Lambda\Result\PublishLayerVersionResponse;
use AsyncAws\Lambda\ValueObject\LayerVersionContentInput;
use Symfony\Component\HttpClient\MockHttpClient;

class LambdaClientTest extends TestCase
{
    public function testAddLayerVersionPermission(): void
    {
        $client = new LambdaClient([], new NullProvider(), new MockHttpClient());

        $input = new AddLayerVersionPermissionRequest([
            'LayerName' => 'change me',
            'VersionNumber' => 1337,
            'StatementId' => 'change me',
            'Action' => 'change me',
            'Principal' => 'change me',
        ]);
        $result = $client->AddLayerVersionPermission($input);

        self::assertInstanceOf(AddLayerVersionPermissionResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDeleteFunction(): void
    {
        $client = new LambdaClient([], new NullProvider(), new MockHttpClient());

        $input = new DeleteFunctionRequest([
            'FunctionName' => 'change me',
        ]);
        $result = $client->DeleteFunction($input);

        self::assertInstanceOf(Result::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetAlias(): void
    {
        $client = new LambdaClient([], new NullProvider(), new MockHttpClient());

        $input = new GetAliasRequest([
            'FunctionName' => 'change me',
            'Name' => 'change me',
        ]);
        $result = $client->getAlias($input);

        self::assertInstanceOf(AliasConfiguration::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetFunctionConfiguration(): void
    {
        $client = new LambdaClient([], new NullProvider(), new MockHttpClient());

        $input = new GetFunctionConfigurationRequest([
            'FunctionName' => 'change me',
        ]);
        $result = $client->getFunctionConfiguration($input);

        self::assertInstanceOf(FunctionConfiguration::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testInvoke(): void
    {
        $client = new LambdaClient([], new NullProvider(), new MockHttpClient());

        $input = new InvocationRequest([
            'FunctionName' => 'change me',
        ]);
        $result = $client->Invoke($input);

        self::assertInstanceOf(InvocationResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListAliases(): void
    {
        $client = new LambdaClient([], new NullProvider(), new MockHttpClient());

        $input = new ListAliasesRequest([
            'FunctionName' => 'change me',
        ]);
        $result = $client->listAliases($input);

        self::assertInstanceOf(ListAliasesResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListEventSourceMappings(): void
    {
        $client = new LambdaClient([], new NullProvider(), new MockHttpClient());

        $input = new ListEventSourceMappingsRequest([
        ]);
        $result = $client->listEventSourceMappings($input);

        self::assertInstanceOf(ListEventSourceMappingsResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListFunctions(): void
    {
        $client = new LambdaClient([], new NullProvider(), new MockHttpClient());

        $input = new ListFunctionsRequest([
        ]);
        $result = $client->ListFunctions($input);

        self::assertInstanceOf(ListFunctionsResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListLayerVersions(): void
    {
        $client = new LambdaClient([], new NullProvider(), new MockHttpClient());

        $input = new ListLayerVersionsRequest([
            'LayerName' => 'change me',
        ]);
        $result = $client->ListLayerVersions($input);

        self::assertInstanceOf(ListLayerVersionsResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListVersionsByFunction(): void
    {
        $client = new LambdaClient([], new NullProvider(), new MockHttpClient());

        $input = new ListVersionsByFunctionRequest([
            'FunctionName' => 'change me',
        ]);
        $result = $client->ListVersionsByFunction($input);

        self::assertInstanceOf(ListVersionsByFunctionResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testPublishLayerVersion(): void
    {
        $client = new LambdaClient([], new NullProvider(), new MockHttpClient());

        $input = new PublishLayerVersionRequest([
            'LayerName' => 'change me',

            'Content' => new LayerVersionContentInput([
                'S3Bucket' => 'change me',
                'S3Key' => 'change me',
                'S3ObjectVersion' => 'change me',
                'ZipFile' => 'change me',
            ]),
        ]);
        $result = $client->PublishLayerVersion($input);

        self::assertInstanceOf(PublishLayerVersionResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testPutFunctionConcurrency(): void
    {
        $client = new LambdaClient([], new NullProvider(), new MockHttpClient());

        $input = new PutFunctionConcurrencyRequest([
            'FunctionName' => 'change me',
            'ReservedConcurrentExecutions' => 1337,
        ]);
        $result = $client->putFunctionConcurrency($input);

        self::assertInstanceOf(Concurrency::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testUpdateFunctionConfiguration(): void
    {
        $client = new LambdaClient([], new NullProvider(), new MockHttpClient());

        $input = new UpdateFunctionConfigurationRequest([
            'FunctionName' => 'test-func',
        ]);
        $result = $client->updateFunctionConfiguration($input);

        self::assertInstanceOf(FunctionConfiguration::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
