<?php

namespace AsyncAws\AppSync\Tests\Unit\Input;

use AsyncAws\AppSync\Input\StartSchemaCreationRequest;
use AsyncAws\Core\Test\TestCase;

class StartSchemaCreationRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new StartSchemaCreationRequest([
            'apiId' => 'api123',
            'definition' => 'schemaDefinition',
        ]);

        self::assertEquals('c2NoZW1hRGVmaW5pdGlvbg==', base64_encode($input->getDefinition()));

        // see https://docs.aws.amazon.com/appsync/latest/APIReference/API_StartSchemaCreation.html
        $expected = '
            POST /v1/apis/api123/schemacreation HTTP/1.1
            Content-type: application/json

            {
               "definition": "c2NoZW1hRGVmaW5pdGlvbg=="
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
