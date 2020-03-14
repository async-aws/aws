<?php

namespace AsyncAws\CloudFormation\Tests\Unit\Input;

use AsyncAws\CloudFormation\Input\DescribeStackEventsInput;
use AsyncAws\Core\Test\TestCase;

class DescribeStackEventsInputTest extends TestCase
{
    /**
     * @see https://docs.aws.amazon.com/AWSCloudFormation/latest/APIReference/API_DescribeStackEvents.html#API_DescribeStackEvents_Errors
     */
    public function testRequestBody(): void
    {
        $input = new DescribeStackEventsInput([
            'StackName' => 'MyStack',
        ]);

        $expected = '
            Action=DescribeStackEvents
            &Version=2010-05-15
            &StackName=MyStack
        ';

        self::assertHttpFormEqualsHttpForm($expected, $input->request()->getBody()->stringify());
    }
}
