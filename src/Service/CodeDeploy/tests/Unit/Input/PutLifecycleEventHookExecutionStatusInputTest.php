<?php

namespace AsyncAws\CodeDeploy\Tests\Unit\Input;

use AsyncAws\CodeDeploy\Input\PutLifecycleEventHookExecutionStatusInput;
use AsyncAws\Core\Test\TestCase;

class PutLifecycleEventHookExecutionStatusInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new PutLifecycleEventHookExecutionStatusInput([
            'deploymentId' => '123',
            'lifecycleEventHookExecutionId' => 'abc',
            'status' => 'Succeeded',
        ]);

        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
x-amz-target: CodeDeploy_20141006.PutLifecycleEventHookExecutionStatus

{
    "deploymentId": "123",
    "lifecycleEventHookExecutionId": "abc",
    "status": "Succeeded"
}
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
