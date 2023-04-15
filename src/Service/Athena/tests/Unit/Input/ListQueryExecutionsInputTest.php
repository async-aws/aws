<?php

namespace AsyncAws\Athena\Tests\Unit\Input;

use AsyncAws\Athena\Input\ListQueryExecutionsInput;
use AsyncAws\Core\Test\TestCase;

class ListQueryExecutionsInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListQueryExecutionsInput([
            'NextToken' => 'iad-9633',
            'MaxResults' => 1337,
            'WorkGroup' => 'iadinternatiaonal',
        ]);

        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_ListQueryExecutions.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
x-amz-target: AmazonAthena.ListQueryExecutions

{
    "NextToken": "iad-9633",
    "MaxResults": 1337,
    "WorkGroup": "iadinternatiaonal"
}
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
