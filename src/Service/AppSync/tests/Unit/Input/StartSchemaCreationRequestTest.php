<?php

namespace AsyncAws\AppSync\Tests\Unit\Input;

use AsyncAws\AppSync\Input\StartSchemaCreationRequest;
use AsyncAws\Core\Test\TestCase;

class StartSchemaCreationRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new StartSchemaCreationRequest([
            'apiId' => 'change me',
            'definition' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/appsync/latest/APIReference/API_StartSchemaCreation.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/json

            {
            "change": "it"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
