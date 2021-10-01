<?php

namespace AsyncAws\StepFunctions\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\StepFunctions\Input\SendTaskFailureInput;
use AsyncAws\StepFunctions\Input\SendTaskHeartbeatInput;
use AsyncAws\StepFunctions\Input\SendTaskSuccessInput;
use AsyncAws\StepFunctions\Input\StartExecutionInput;
use AsyncAws\StepFunctions\Result\SendTaskFailureOutput;
use AsyncAws\StepFunctions\Result\SendTaskHeartbeatOutput;
use AsyncAws\StepFunctions\Result\SendTaskSuccessOutput;
use AsyncAws\StepFunctions\Result\StartExecutionOutput;
use AsyncAws\StepFunctions\StepFunctionsClient;
use Symfony\Component\HttpClient\MockHttpClient;

class StepFunctionsClientTest extends TestCase
{
    public function testSendTaskFailure(): void
    {
        $client = new StepFunctionsClient([], new NullProvider(), new MockHttpClient());

        $input = new SendTaskFailureInput([
            'taskToken' => 'change me',

        ]);
        $result = $client->sendTaskFailure($input);

        self::assertInstanceOf(SendTaskFailureOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testSendTaskHeartbeat(): void
    {
        $client = new StepFunctionsClient([], new NullProvider(), new MockHttpClient());

        $input = new SendTaskHeartbeatInput([
            'taskToken' => 'change me',
        ]);
        $result = $client->sendTaskHeartbeat($input);

        self::assertInstanceOf(SendTaskHeartbeatOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testSendTaskSuccess(): void
    {
        $client = new StepFunctionsClient([], new NullProvider(), new MockHttpClient());

        $input = new SendTaskSuccessInput([
            'taskToken' => 'change me',
            'output' => 'change me',
        ]);
        $result = $client->sendTaskSuccess($input);

        self::assertInstanceOf(SendTaskSuccessOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testStartExecution(): void
    {
        $client = new StepFunctionsClient([], new NullProvider(), new MockHttpClient());

        $input = new StartExecutionInput([
            'stateMachineArn' => 'arn:sfn',
            'name' => 'qwertyuiop',
            'input' => '{"foo": "bar"}',
        ]);
        $result = $client->startExecution($input);

        self::assertInstanceOf(StartExecutionOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
