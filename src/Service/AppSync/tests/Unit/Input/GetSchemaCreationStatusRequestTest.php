<?php

namespace AsyncAws\AppSync\Tests\Unit\Input;

use AsyncAws\AppSync\Input\GetSchemaCreationStatusRequest;
use AsyncAws\Core\Test\TestCase;

class GetSchemaCreationStatusRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetSchemaCreationStatusRequest([
            'apiId' => 'api123',
        ]);

        // see https://docs.aws.amazon.com/appsync/latest/APIReference/API_GetSchemaCreationStatus.html
        $expected = '
            GET /v1/apis/api123/schemacreation HTTP/1.1
            Content-Type: application/json
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
