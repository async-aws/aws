<?php

namespace AsyncAws\StepFunctions\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\StepFunctions\Input\StartExecutionInput;
use AsyncAws\StepFunctions\StepFunctionsClient;

class StepFunctionsClientTest extends TestCase
{
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

        self::assertSame('changeIt', $result->getexecutionArn());
        // self::assertTODO(expected, $result->getstartDate());
    }

    private function getClient(): StepFunctionsClient
    {
        self::fail('Not implemented');

        return new StepFunctionsClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
