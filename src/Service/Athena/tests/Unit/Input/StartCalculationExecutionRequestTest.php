<?php

namespace AsyncAws\Athena\Tests\Unit\Input;

use AsyncAws\Athena\Input\StartCalculationExecutionRequest;
use AsyncAws\Athena\ValueObject\CalculationConfiguration;
use AsyncAws\Core\Test\TestCase;

class StartCalculationExecutionRequestTest extends TestCase
{
    /**
     * @group legacy
     *
     * @expectedDeprecation The property "CalculationConfiguration" of "%s" is deprecated by AWS.
     */
    public function testRequest(): void
    {
        $input = new StartCalculationExecutionRequest([
            'SessionId' => 'iad-session-25463',
            'Description' => 'iad international',
            'CalculationConfiguration' => new CalculationConfiguration([
                'CodeBlock' => 'IAD',
            ]),
            'CodeBlock' => 'IAD',
            'ClientRequestToken' => 'i@d-2023',
        ]);

        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_StartCalculationExecution.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
x-amz-target: AmazonAthena.StartCalculationExecution
Accept: application/json

{
    "SessionId": "iad-session-25463",
    "Description": "iad international",
    "CalculationConfiguration": {
       "CodeBlock": "IAD"
    },
    "CodeBlock": "IAD",
    "ClientRequestToken": "i@d-2023"
}
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
