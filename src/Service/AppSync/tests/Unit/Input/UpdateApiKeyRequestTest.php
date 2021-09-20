<?php

namespace AsyncAws\AppSync\Tests\Unit\Input;

use AsyncAws\AppSync\Input\UpdateApiKeyRequest;
use AsyncAws\Core\Test\TestCase;

class UpdateApiKeyRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new UpdateApiKeyRequest([
            'apiId' => 'change me',
            'id' => 'change me',
            'description' => 'change me',
            'expires' => 1337,
        ]);

        // see https://docs.aws.amazon.com/appsync/latest/APIReference/API_UpdateApiKey.html
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
