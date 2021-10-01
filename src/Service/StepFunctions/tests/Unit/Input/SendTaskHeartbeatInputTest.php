<?php

namespace AsyncAws\StepFunctions\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\StepFunctions\Input\SendTaskHeartbeatInput;

class SendTaskHeartbeatInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new SendTaskHeartbeatInput([
            'taskToken' => 'qwertyuiop',
        ]);

        // see https://docs.aws.amazon.com/step-functions/latest/apireference/API_SendTaskHeartbeat.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.0
            X-Amz-Target: AWSStepFunctions.SendTaskHeartbeat

            {
                "taskToken": "qwertyuiop"
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
