<?php

namespace AsyncAws\Sns\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sns\Input\CreatePlatformEndpointInput;

class CreatePlatformEndpointInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CreatePlatformEndpointInput([
            'PlatformApplicationArn' => 'arn:aws:sns:us-west-2:123456789012:app/GCM/gcmpushapp',
            'Token' => 'APA91bGi7fFachkC1xjlqT66VYEucGHochmf1VQAr9k...jsM0PKPxKhddCzx6paEsyay9Zn3D4wNUJb8m6HZrBEXAMPLE',
            'CustomUserData' => 'UserId=27576823',
            'Attributes' => ['change me' => 'change me'],
        ]);

        // see https://docs.aws.amazon.com/sns/latest/api/API_CreatePlatformEndpoint.html
        $expected = '
    POST / HTTP/1.0
    Content-Type: application/x-www-form-urlencoded

    Action=CreatePlatformEndpoint
    &Version=2010-03-31
    &Attributes.entry.1.key=change+me
    &Attributes.entry.1.value=change+me
    &CustomUserData=UserId%3D27576823
    &PlatformApplicationArn=arn%3Aaws%3Asns%3Aus-west-2%3A123456789012%3Aapp%2FGCM%2Fgcmpushapp
    &Token=APA91bGi7fFachkC1xjlqT66VYEucGHochmf1VQAr9k...jsM0PKPxKhddCzx6paEsyay9Zn3D4wNUJb8m6HZrBEXAMPLE
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
