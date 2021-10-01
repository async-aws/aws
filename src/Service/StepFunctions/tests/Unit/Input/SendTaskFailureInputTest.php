<?php

namespace AsyncAws\StepFunctions\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\StepFunctions\Input\SendTaskFailureInput;

class SendTaskFailureInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new SendTaskFailureInput([
            'taskToken' => 'qwertyuiop',
            'error' => 'err_foo',
            'cause' => 'Crash!',
        ]);

        // see https://docs.aws.amazon.com/step-functions/latest/apireference/API_SendTaskFailure.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.0
            X-Amz-Target: AWSStepFunctions.SendTaskFailure

            {
                "taskToken": "qwertyuiop",
                "error": "err_foo",
                "cause": "Crash!"
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
