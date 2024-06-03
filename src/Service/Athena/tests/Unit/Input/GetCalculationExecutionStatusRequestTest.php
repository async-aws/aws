<?php

namespace AsyncAws\Athena\Tests\Unit\Input;

use AsyncAws\Athena\Input\GetCalculationExecutionStatusRequest;
use AsyncAws\Core\Test\TestCase;

class GetCalculationExecutionStatusRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetCalculationExecutionStatusRequest([
            'CalculationExecutionId' => '125-5rte41',
        ]);

        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_GetCalculationExecutionStatus.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: AmazonAthena.GetCalculationExecutionStatus
            Accept: application/json

            {
            "CalculationExecutionId": "125-5rte41"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
