<?php

namespace AsyncAws\CloudFormation\Tests\Unit\Input;

use AsyncAws\CloudFormation\Input\DescribeStackEventsInput;
use PHPUnit\Framework\TestCase;

class DescribeStackEventsInputTest extends TestCase
{
    public function testRequestBody(): void
    {
        self::markTestIncomplete('Not implemented');

        $input = new DescribeStackEventsInput([
            'StackName' => 'change me',
            'NextToken' => 'change me',
        ]);

        $expected = trim('
        Action=DescribeStackEvents
        &Version=2010-05-15
        &ChangeIt=Change+it
                        ');

        self::assertEquals($expected, \str_replace('&', "\n&", $input->requestBody()));
    }
}
