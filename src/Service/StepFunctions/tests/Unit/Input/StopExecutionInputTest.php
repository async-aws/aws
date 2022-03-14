<?php

namespace AsyncAws\StepFunctions\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\StepFunctions\Input\StopExecutionInput;

class StopExecutionInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new StopExecutionInput([
            'executionArn' => 'arn:foo',
            'error' => 'some error',
            'cause' => 'some cause',
        ]);

        // see https://docs.aws.amazon.com/step-functions/latest/apireference/API_StopExecution.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.0
            X-Amz-Target: AWSStepFunctions.StopExecution

            {
                "executionArn": "arn:foo",
                "error": "some error",
                "cause": "some cause"
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
