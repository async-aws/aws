<?php

namespace AsyncAws\Sns\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sns\Input\CreateTopicInput;

class CreateTopicInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CreateTopicInput([
            'Name' => 'My-Topic',
        ]);

        // see https://docs.aws.amazon.com/sns/latest/api/API_CreateTopic.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=CreateTopic
            &Name=My-Topic
            &Version=2010-03-31
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
