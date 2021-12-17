<?php

namespace AsyncAws\CloudFormation\Tests\Unit\Input;

use AsyncAws\CloudFormation\Input\DescribeStackDriftDetectionStatusInput;
use AsyncAws\Core\Test\TestCase;

class DescribeStackDriftDetectionStatusInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DescribeStackDriftDetectionStatusInput([
            'StackDriftDetectionId' => 'b78ac9b0-dec1-11e7-a451-503a3example',
        ]);

        // see https://docs.aws.amazon.com/AWSCloudFormation/latest/APIReference/API_DescribeStackDriftDetectionStatus.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=DescribeStackDriftDetectionStatus
            &StackDriftDetectionId=b78ac9b0-dec1-11e7-a451-503a3example
            &Version=2010-05-15
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
