<?php

namespace AsyncAws\CloudFormation\Tests\Unit\Input;

use AsyncAws\CloudFormation\Input\ListExportsInput;
use AsyncAws\Core\Test\TestCase;

class ListExportsInputTest extends TestCase
{
    /**
     * @see https://docs.aws.amazon.com/AWSCloudFormation/latest/APIReference/API_ListExports.html
     */
    public function testRequest(): void
    {
        $input = new ListExportsInput([]);

        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=ListExports
            &Version=2010-05-15
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }

    public function testRequestWithNextToken(): void
    {
        $input = new ListExportsInput([
            'NextToken' => 'abc123',
        ]);

        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-www-form-urlencoded

            Action=ListExports
            &Version=2010-05-15
            &NextToken=abc123
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
