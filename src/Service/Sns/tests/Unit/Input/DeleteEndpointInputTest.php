<?php

namespace AsyncAws\Sns\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sns\Input\DeleteEndpointInput;

class DeleteEndpointInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DeleteEndpointInput([
            'EndpointArn' => 'arn:aws:sns:us-west-2:123456789012:endpoint/GCM/gcmpushapp/5e3e9847-3183-3f18-a7e8-671c3a57d4b3',
        ]);

        // see https://docs.aws.amazon.com/sns/latest/api/API_DeleteEndpoint.html
        $expected = '
    POST / HTTP/1.0
    Content-Type: application/x-www-form-urlencoded

    Action=DeleteEndpoint
    &EndpointArn=arn%3Aaws%3Asns%3Aus-west-2%3A123456789012%3Aendpoint%2FGCM%2Fgcmpushapp%2F5e3e9847-3183-3f18-a7e8-671c3a57d4b3
    &Version=2010-03-31
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
