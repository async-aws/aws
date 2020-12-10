<?php

namespace AsyncAws\Ecr\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ecr\Input\GetAuthorizationTokenRequest;

class GetAuthorizationTokenRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetAuthorizationTokenRequest();

        // see https://docs.aws.amazon.com/AmazonECR/latest/APIReference/API_GetAuthorizationToken.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: AmazonEC2ContainerRegistry_V20150921.GetAuthorizationToken

            {}
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
