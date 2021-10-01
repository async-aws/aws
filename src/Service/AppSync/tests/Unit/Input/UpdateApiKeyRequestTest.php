<?php

namespace AsyncAws\AppSync\Tests\Unit\Input;

use AsyncAws\AppSync\Input\UpdateApiKeyRequest;
use AsyncAws\Core\Test\TestCase;

class UpdateApiKeyRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new UpdateApiKeyRequest([
            'apiId' => 'api123',
            'id' => 'keyId',
            'description' => 'Description here',
            'expires' => 1337,
        ]);

        // see https://docs.aws.amazon.com/appsync/latest/APIReference/API_UpdateApiKey.html
        $expected = '
            POST /v1/apis/api123/apikeys/keyId HTTP/1.1
            Content-type: application/json

            {
               "description": "Description here",
               "expires": 1337
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
