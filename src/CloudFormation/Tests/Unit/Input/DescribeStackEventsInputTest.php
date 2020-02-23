<?php

namespace AsyncAws\CloudFormation\Tests\Unit\Input;

use AsyncAws\CloudFormation\Input\DescribeStackEventsInput;
use AsyncAws\Core\Test\TestCase;

class DescribeStackEventsInputTest extends TestCase
{
    public function testRequestBody(): void
    {
        self::markTestIncomplete('Not implemented');

        $input = new DescribeStackEventsInput([
            'StackName' => 'change me',
            'NextToken' => 'change me',
        ]);

        $expected = '
            Action=DescribeStackEvents
            &Version=2010-05-15
            &ChangeIt=Change+it
        ';

        self::assertHttpFormEqualsHttpForm($expected, $input->requestBody());
    }
}
