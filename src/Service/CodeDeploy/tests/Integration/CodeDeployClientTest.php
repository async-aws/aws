<?php

namespace AsyncAws\CodeDeploy\Tests\Integration;

use AsyncAws\CodeDeploy\CodeDeployClient;
use AsyncAws\CodeDeploy\Input\PutLifecycleEventHookExecutionStatusInput;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;

class CodeDeployClientTest extends TestCase
{
    public function testPutLifecycleEventHookExecutionStatus(): void
    {
        $client = $this->getClient();

        $input = new PutLifecycleEventHookExecutionStatusInput([
            'deploymentId' => 'change me',
            'lifecycleEventHookExecutionId' => 'change me',
            'status' => 'change me',
        ]);
        $result = $client->PutLifecycleEventHookExecutionStatus($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getlifecycleEventHookExecutionId());
    }

    private function getClient(): CodeDeployClient
    {
        self::fail('Not implemented');

        return new CodeDeployClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
