<?php

namespace AsyncAws\StepFunctions\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\StepFunctions\Input\SendTaskFailureInput;
use AsyncAws\StepFunctions\Input\SendTaskHeartbeatInput;
use AsyncAws\StepFunctions\Input\SendTaskSuccessInput;
use AsyncAws\StepFunctions\Input\StartExecutionInput;
use AsyncAws\StepFunctions\StepFunctionsClient;

class StepFunctionsClientTest extends TestCase
{
    public function testSendTaskFailure(): void
    {
        self::markTestIncomplete('Cannot test SendTaskFailure without the ability to create machines available.');

        $client = $this->getClient();

        $input = new SendTaskFailureInput([
            'taskToken' => 'change me',
            'error' => 'change me',
            'cause' => 'change me',
        ]);
        $result = $client->sendTaskFailure($input);

        $result->resolve();
    }

    public function testSendTaskHeartbeat(): void
    {
        self::markTestIncomplete('Cannot test SendTaskHeartbeat without the ability to create machines available.');

        $client = $this->getClient();

        $input = new SendTaskHeartbeatInput([
            'taskToken' => 'change me',
        ]);
        $result = $client->sendTaskHeartbeat($input);

        $result->resolve();
    }

    public function testSendTaskSuccess(): void
    {
        self::markTestIncomplete('Cannot test SendTaskSuccess without the ability to create machines available.');

        $client = $this->getClient();

        $input = new SendTaskSuccessInput([
            'taskToken' => 'change me',
            'output' => 'change me',
        ]);
        $result = $client->sendTaskSuccess($input);

        $result->resolve();
    }

    public function testStartExecution(): void
    {
        self::markTestIncomplete('Cannot test StartExecution without the ability to create machines available.');

        $client = $this->getClient();

        $input = new StartExecutionInput([
            'stateMachineArn' => 'change me',
            'name' => 'change me',
            'input' => 'change me',
            'traceHeader' => 'change me',
        ]);
        $result = $client->startExecution($input);

        $result->resolve();
    }

    private function getClient(): StepFunctionsClient
    {
        self::fail('Not implemented');

        return new StepFunctionsClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
