<?php

namespace AsyncAws\CloudFormation\Tests\Unit\Input;

use AsyncAws\CloudFormation\Input\DescribeStacksInput;
use AsyncAws\Core\Test\TestCase;

class DescribeStacksInputTest extends TestCase
{
    public function testRequestBody(): void
    {
        $input = new DescribeStacksInput([
            'StackName' => 'foo',
            'NextToken' => 'bar',
        ]);

        // see https://docs.aws.amazon.com/CloudFormation/latest/APIReference/API_DescribeStacks.html
        $expected = '
            Action=DescribeStacks
            &Version=2010-05-15
            &StackName=foo
            &NextToken=bar
        ';

        self::assertHttpFormEqualsHttpForm($expected, $input->request()->getBody()->stringify());
    }
}
