<?php

namespace AsyncAws\Athena\Tests\Unit\Input;

use AsyncAws\Athena\Input\GetNamedQueryInput;
use AsyncAws\Core\Test\TestCase;

class GetNamedQueryInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetNamedQueryInput([
            'NamedQueryId' => 'test_query',
        ]);

        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_GetNamedQuery.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: AmazonAthena.GetNamedQuery
            Accept: application/json

            {
            "NamedQueryId": "test_query"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
