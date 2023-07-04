<?php

namespace AsyncAws\Sns\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sns\Input\ListTopicsInput;

class ListTopicsInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListTopicsInput([
            'NextToken' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/sns/latest/api/API_ListTopics.html
        $expected = <<<TEXT
POST / HTTP/1.0
Content-Type: application/x-www-form-urlencoded

Action=ListTopics
NextToken=change+me
&Version=2010-03-31
TEXT;

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
