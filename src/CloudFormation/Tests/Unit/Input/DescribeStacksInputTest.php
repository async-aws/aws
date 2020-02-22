<?php

declare(strict_types=1);

namespace AsyncAws\CloudFormation\Tests\Unit\Input;

use AsyncAws\CloudFormation\Input\DescribeStacksInput;
use PHPUnit\Framework\TestCase;

class DescribeStacksInputTest extends TestCase
{
    public function testRequestBody()
    {
        $input = new DescribeStacksInput(['StackName' => 'foo', 'NextToken' => 'bar']);
        $requestBody = $input->requestBody();

        self::assertStringContainsString('StackName=foo', $requestBody);
        self::assertStringContainsString('NextToken=bar', $requestBody);
    }
}
