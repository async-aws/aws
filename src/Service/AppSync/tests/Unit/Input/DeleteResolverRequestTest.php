<?php

namespace AsyncAws\AppSync\Tests\Unit\Input;

use AsyncAws\AppSync\Input\DeleteResolverRequest;
use AsyncAws\Core\Test\TestCase;

class DeleteResolverRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new DeleteResolverRequest([
            'apiId' => 'change me',
            'typeName' => 'change me',
            'fieldName' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/appsync/latest/APIReference/API_DeleteResolver.html
        $expected = '
            DELETE / HTTP/1.0
            Content-Type: application/json

            {
            "change": "it"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
