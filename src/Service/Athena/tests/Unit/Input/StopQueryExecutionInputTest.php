<?php

namespace AsyncAws\Athena\Tests\Unit\Input;

use AsyncAws\Athena\Input\StopQueryExecutionInput;
use AsyncAws\Core\Test\TestCase;

class StopQueryExecutionInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new StopQueryExecutionInput([
            'QueryExecutionId' => 'my-query-id-24563',
        ]);

        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_StopQueryExecution.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: AmazonAthena.StopQueryExecution

            {
               "QueryExecutionId": "my-query-id-24563"
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
