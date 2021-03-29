<?php

namespace AsyncAws\Lambda\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Result;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Input\AddLayerVersionPermissionRequest;
use AsyncAws\Lambda\Input\DeleteFunctionRequest;
use AsyncAws\Lambda\Input\InvocationRequest;
use AsyncAws\Lambda\Input\ListFunctionsRequest;
use AsyncAws\Lambda\Input\ListLayerVersionsRequest;
use AsyncAws\Lambda\Input\ListVersionsByFunctionRequest;
use AsyncAws\Lambda\Input\PublishLayerVersionRequest;
use AsyncAws\Lambda\LambdaClient;
use AsyncAws\Lambda\Result\AddLayerVersionPermissionResponse;
use AsyncAws\Lambda\Result\InvocationResponse;
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
}
