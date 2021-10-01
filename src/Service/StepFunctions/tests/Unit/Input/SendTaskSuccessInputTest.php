<?php

namespace AsyncAws\StepFunctions\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\StepFunctions\Input\SendTaskSuccessInput;

class SendTaskSuccessInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new SendTaskSuccessInput([
            'taskToken' => 'qwertyuiop',
            'output' => '{"success": ":partyparrot:"}',
        ]);

        // see https://docs.aws.amazon.com/step-functions/latest/apireference/API_SendTaskSuccess.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.0
            X-Amz-Target: AWSStepFunctions.SendTaskSuccess

            {
                "taskToken": "qwertyuiop",
                "output": "{\"success\": \":partyparrot:\"}"
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
