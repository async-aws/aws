<?php

namespace AsyncAws\CodeCommit\Tests\Unit\Input;

use AsyncAws\CodeCommit\Input\GetBranchInput;
use AsyncAws\Core\Test\TestCase;

class GetBranchInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetBranchInput([
            'repositoryName' => 'MyFirstRepository',
            'branchName' => 'main',
        ]);

        // see https://docs.aws.amazon.com/codecommit/latest/APIReference/API_GetBranch.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: CodeCommit_20150413.GetBranch

            {
            "repositoryName": "MyFirstRepository",
            "branchName": "main"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
