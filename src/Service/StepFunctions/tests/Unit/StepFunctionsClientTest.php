<?php

namespace AsyncAws\StepFunctions\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\StepFunctions\Input\StartExecutionInput;
use AsyncAws\StepFunctions\Result\StartExecutionOutput;
use AsyncAws\StepFunctions\StepFunctionsClient;
use Symfony\Component\HttpClient\MockHttpClient;

class StepFunctionsClientTest extends TestCase
{
    public function testStartExecution(): void
    {
        $client = new StepFunctionsClient([], new NullProvider(), new MockHttpClient());

        $input = new StartExecutionInput([
            'stateMachineArn' => 'arn:sfn',
            'name' => 'qwertyuiop',
            'input' => '{"foo": "bar"}',
        ]);
        $result = $client->StartExecution($input);

        self::assertInstanceOf(StartExecutionOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
