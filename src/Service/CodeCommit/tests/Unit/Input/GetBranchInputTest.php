<?php

namespace AsyncAws\CodeCommit\Tests\Unit\Input;

use AsyncAws\CodeCommit\Input\GetBranchInput;
use AsyncAws\Core\Test\TestCase;

class GetBranchInputTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new GetBranchInput([
            'repositoryName' => 'change me',
            'branchName' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/codecommit/latest/APIReference/API_GetBranch.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1

            {
            "change": "it"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
