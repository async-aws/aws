<?php

namespace AsyncAws\AppSync\Tests\Unit\Input;

use AsyncAws\AppSync\Input\GetSchemaCreationStatusRequest;
use AsyncAws\Core\Test\TestCase;

class GetSchemaCreationStatusRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new GetSchemaCreationStatusRequest([
            'apiId' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/appsync/latest/APIReference/API_GetSchemaCreationStatus.html
        $expected = '
            GET / HTTP/1.0
            Content-Type: application/json

            {
            "change": "it"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
