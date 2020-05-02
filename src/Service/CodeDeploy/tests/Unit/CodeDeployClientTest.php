<?php

namespace AsyncAws\CodeDeploy\Tests\Unit;

use AsyncAws\CodeDeploy\CodeDeployClient;
use AsyncAws\CodeDeploy\Input\PutLifecycleEventHookExecutionStatusInput;
use AsyncAws\CodeDeploy\Result\PutLifecycleEventHookExecutionStatusOutput;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class CodeDeployClientTest extends TestCase
{
    public function testPutLifecycleEventHookExecutionStatus(): void
    {
        $client = new CodeDeployClient([], new NullProvider(), new MockHttpClient());

        $input = new PutLifecycleEventHookExecutionStatusInput([
            'deploymentId' => '123',
            'lifecycleEventHookExecutionId' => 'abc',
            'status' => 'Succeeded',
        ]);
        $result = $client->PutLifecycleEventHookExecutionStatus($input);

        self::assertInstanceOf(PutLifecycleEventHookExecutionStatusOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
