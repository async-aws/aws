<?php

namespace AsyncAws\AppSync\Tests\Unit\Input;

use AsyncAws\AppSync\Input\DeleteResolverRequest;
use AsyncAws\Core\Test\TestCase;

class DeleteResolverRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DeleteResolverRequest([
            'apiId' => 'api123',
            'typeName' => 'type',
            'fieldName' => 'field',
        ]);

        // see https://docs.aws.amazon.com/appsync/latest/APIReference/API_DeleteResolver.html
        $expected = '
            DELETE /v1/apis/api123/types/type/resolvers/field HTTP/1.1
            Content-Type: application/json
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
