<?php

namespace AsyncAws\Athena\Tests\Unit\Input;

use AsyncAws\Athena\Input\GetCalculationExecutionRequest;
use AsyncAws\Core\Test\TestCase;

class GetCalculationExecutionRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetCalculationExecutionRequest([
            'CalculationExecutionId' => '145226',
        ]);

        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_GetCalculationExecution.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: AmazonAthena.GetCalculationExecution

            {
            "CalculationExecutionId": "145226"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
