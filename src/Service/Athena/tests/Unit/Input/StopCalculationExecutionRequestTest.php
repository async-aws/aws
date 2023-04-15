<?php

namespace AsyncAws\Athena\Tests\Unit\Input;

use AsyncAws\Athena\Input\StopCalculationExecutionRequest;
use AsyncAws\Core\Test\TestCase;

class StopCalculationExecutionRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new StopCalculationExecutionRequest([
            'CalculationExecutionId' => 'id14562',
        ]);

        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_StopCalculationExecution.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: AmazonAthena.StopCalculationExecution

            {
            "CalculationExecutionId": "id14562"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
