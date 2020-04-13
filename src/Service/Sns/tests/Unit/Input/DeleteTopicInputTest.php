<?php

namespace AsyncAws\Sns\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sns\Input\DeleteTopicInput;

class DeleteTopicInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DeleteTopicInput([
            'TopicArn' => 'arn:aws:sns:us-east-2:123456789012:My-Topic',
        ]);

        // see https://docs.aws.amazon.com/sns/latest/api/API_DeleteTopic.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=DeleteTopic
            &TopicArn=arn%3Aaws%3Asns%3Aus-east-2%3A123456789012%3AMy-Topic
            &Version=2010-03-31
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
