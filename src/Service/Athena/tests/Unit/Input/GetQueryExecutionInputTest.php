<?php

namespace AsyncAws\Athena\Tests\Unit\Input;

use AsyncAws\Athena\Input\GetQueryExecutionInput;
use AsyncAws\Core\Test\TestCase;

class GetQueryExecutionInputTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new GetQueryExecutionInput([
            'QueryExecutionId' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/athena/latest/APIReference/Welcome.html/API_GetQueryExecution.html
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
