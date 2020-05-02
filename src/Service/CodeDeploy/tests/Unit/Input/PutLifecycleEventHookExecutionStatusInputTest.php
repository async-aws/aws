<?php

namespace AsyncAws\CodeDeploy\Tests\Unit\Input;

use AsyncAws\CodeDeploy\Input\PutLifecycleEventHookExecutionStatusInput;
use AsyncAws\Core\Test\TestCase;

class PutLifecycleEventHookExecutionStatusInputTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new PutLifecycleEventHookExecutionStatusInput([
            'deploymentId' => 'change me',
            'lifecycleEventHookExecutionId' => 'change me',
            'status' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/codedeploy/latest/APIReference/API_PutLifecycleEventHookExecutionStatus.html
        $expected = '
                    POST / HTTP/1.0
                    Content-Type: application/x-amz-json-1.1

                    {
            "change": "it"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
