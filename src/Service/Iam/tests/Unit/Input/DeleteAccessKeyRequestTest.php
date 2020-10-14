<?php

namespace AsyncAws\Iam\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Iam\Input\DeleteAccessKeyRequest;

class DeleteAccessKeyRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DeleteAccessKeyRequest([
            'UserName' => 'Bob',
            'AccessKeyId' => 'AKIDPMS9RO4H3FEXAMPLE',
        ]);

        // see example-1.json from SDK
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            AccessKeyId=AKIDPMS9RO4H3FEXAMPLE&Action=DeleteAccessKey&UserName=Bob&Version=2010-05-08
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
