<?php

namespace AsyncAws\StepFunctions\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\StepFunctions\Input\StartExecutionInput;

class StartExecutionInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new StartExecutionInput([
            'stateMachineArn' => 'arn:foo',
            'name' => 'qwertyuiop',
            'input' => '{}',
        ]);

        // see https://docs.aws.amazon.com/step-functions/latest/apireference/API_StartExecution.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.0
            X-Amz-Target: AWSStepFunctions.StartExecution

            {
                "input": "{}",
                "name": "qwertyuiop",
                "stateMachineArn": "arn:foo"
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
