<?php

namespace AsyncAws\Sns\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sns\Input\UnsubscribeInput;

class UnsubscribeInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new UnsubscribeInput([
            'SubscriptionArn' => 'arn:aws:sns:us-east-2:123456789012:My-Topic:80289ba6-0fd4-4079-afb4-ce8c8260f0ca',
        ]);

        // see https://docs.aws.amazon.com/sns/latest/api/API_Unsubscribe.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=Unsubscribe
            &SubscriptionArn=arn%3Aaws%3Asns%3Aus-east-2%3A123456789012%3AMy-Topic%3A80289ba6-0fd4-4079-afb4-ce8c8260f0ca
            &Version=2010-03-31
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
