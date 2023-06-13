<?php

namespace AsyncAws\Ssm\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ssm\Enum\ParameterType;
use AsyncAws\Ssm\Input\DeleteParameterRequest;
use AsyncAws\Ssm\Input\DeleteParametersRequest;
use AsyncAws\Ssm\Input\GetParameterRequest;
use AsyncAws\Ssm\Input\GetParametersByPathRequest;
use AsyncAws\Ssm\Input\GetParametersRequest;
use AsyncAws\Ssm\Input\PutParameterRequest;
use AsyncAws\Ssm\Result\DeleteParameterResult;
use AsyncAws\Ssm\Result\DeleteParametersResult;
use AsyncAws\Ssm\Result\GetParameterResult;
use AsyncAws\Ssm\Result\GetParametersByPathResult;
use AsyncAws\Ssm\Result\GetParametersResult;
use AsyncAws\Ssm\Result\PutParameterResult;
use AsyncAws\Ssm\SsmClient;
use Symfony\Component\HttpClient\MockHttpClient;

class SsmClientTest extends TestCase
{
    public function testDeleteParameter(): void
    {
        $client = new SsmClient([], new NullProvider(), new MockHttpClient());

        $input = new DeleteParameterRequest([
            'Name' => 'change me',
        ]);
        $result = $client->DeleteParameter($input);

        self::assertInstanceOf(DeleteParameterResult::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDeleteParameters(): void
    {
        $client = new SsmClient([], new NullProvider(), new MockHttpClient());

        $input = new DeleteParametersRequest([
            'Names' => ['change me'],
        ]);
        $result = $client->deleteParameters($input);

        self::assertInstanceOf(DeleteParametersResult::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetParameter(): void
    {
        $client = new SsmClient([], new NullProvider(), new MockHttpClient());

        $input = new GetParameterRequest([
            'Name' => 'change me',
        ]);
        $result = $client->GetParameter($input);

        self::assertInstanceOf(GetParameterResult::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetParameters(): void
    {
        $client = new SsmClient([], new NullProvider(), new MockHttpClient());

        $input = new GetParametersRequest([
            'Names' => ['change me'],
        ]);
        $result = $client->GetParameters($input);

        self::assertInstanceOf(GetParametersResult::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetParametersByPath(): void
    {
        $client = new SsmClient([], new NullProvider(), new MockHttpClient());

        $input = new GetParametersByPathRequest([
            'Path' => 'change me',
        ]);
        $result = $client->GetParametersByPath($input);

        self::assertInstanceOf(GetParametersByPathResult::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testPutParameter(): void
    {
        $client = new SsmClient([], new NullProvider(), new MockHttpClient());

        $input = new PutParameterRequest([
            'Name' => 'change me',

            'Value' => 'change me',
            'Type' => ParameterType::STRING,
        ]);
        $result = $client->PutParameter($input);

        self::assertInstanceOf(PutParameterResult::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
