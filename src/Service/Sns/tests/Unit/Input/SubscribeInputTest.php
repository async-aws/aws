<?php

namespace AsyncAws\Sns\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sns\Input\SubscribeInput;

class SubscribeInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new SubscribeInput([
            'TopicArn' => 'arn:aws:sns:us-west-2:123456789012:MyTopic',
            'Endpoint' => 'arn:aws:sqs:us-west-2:123456789012:MyQueue',
            'Protocol' => 'sqs',
        ]);

        // see https://docs.aws.amazon.com/sns/latest/api/API_Subscribe.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=Subscribe
            &TopicArn=arn%3Aaws%3Asns%3Aus-west-2%3A123456789012%3AMyTopic
            &Endpoint=arn%3Aaws%3Asqs%3Aus-west-2%3A123456789012%3AMyQueue
            &Protocol=sqs
            &Version=2010-03-31
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
