<?php

namespace AsyncAws\Iam\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iam\Input\CreateAccessKeyRequest;

class CreateAccessKeyRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CreateAccessKeyRequest([
            'UserName' => 'Bob',
        ]);

        // see example-1.json from SDK
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=CreateAccessKey&UserName=Bob&Version=2010-05-08
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
