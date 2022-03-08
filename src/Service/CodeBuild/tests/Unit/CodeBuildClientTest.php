<?php

namespace AsyncAws\CodeBuild\Tests\Unit;

use AsyncAws\CodeBuild\CodeBuildClient;
use AsyncAws\CodeBuild\Input\BatchGetBuildsInput;
use AsyncAws\CodeBuild\Input\StartBuildInput;
use AsyncAws\CodeBuild\Input\StopBuildInput;
use AsyncAws\CodeBuild\Result\BatchGetBuildsOutput;
use AsyncAws\CodeBuild\Result\StartBuildOutput;
use AsyncAws\CodeBuild\Result\StopBuildOutput;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class CodeBuildClientTest extends TestCase
{
    public function testBatchGetBuilds(): void
    {
        $client = new CodeBuildClient([], new NullProvider(), new MockHttpClient());

        $input = new BatchGetBuildsInput([
            'ids' => ['change me'],
        ]);
        $result = $client->batchGetBuilds($input);

        self::assertInstanceOf(BatchGetBuildsOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testStartBuild(): void
    {
        $client = new CodeBuildClient([], new NullProvider(), new MockHttpClient());

        $input = new StartBuildInput([
            'projectName' => 'change me',

        ]);
        $result = $client->startBuild($input);

        self::assertInstanceOf(StartBuildOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testStopBuild(): void
    {
        $client = new CodeBuildClient([], new NullProvider(), new MockHttpClient());

        $input = new StopBuildInput([
            'id' => 'change me',
        ]);
        $result = $client->stopBuild($input);

        self::assertInstanceOf(StopBuildOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
