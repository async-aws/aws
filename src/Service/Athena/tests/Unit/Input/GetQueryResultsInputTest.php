<?php

namespace AsyncAws\Athena\Tests\Unit\Input;

use AsyncAws\Athena\Input\GetQueryResultsInput;
use AsyncAws\Core\Test\TestCase;

class GetQueryResultsInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetQueryResultsInput([
            'QueryExecutionId' => 'iad-145r55t-11446',
            'NextToken' => 'iad-next-2563',
            'MaxResults' => 1337,
        ]);

        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_GetQueryResults.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
x-amz-target: AmazonAthena.GetQueryResults
Accept: application/json

{
    "QueryExecutionId": "iad-145r55t-11446",
    "NextToken": "iad-next-2563",
    "MaxResults": 1337
}
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
