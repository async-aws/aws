<?php

namespace AsyncAws\CodeCommit\Tests\Unit\Input;

use AsyncAws\CodeCommit\Input\GetCommitInput;
use AsyncAws\Core\Test\TestCase;

class GetCommitInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetCommitInput([
            'repositoryName' => 'my-super-code-repository',
            'commitId' => 'b58c341f3d493f7fc0b6b95a648a2e2397d0692f',
        ]);

        // see https://docs.aws.amazon.com/codecommit/latest/APIReference/API_GetCommit.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: CodeCommit_20150413.GetCommit
            Accept: application/json

            {
                "commitId": "b58c341f3d493f7fc0b6b95a648a2e2397d0692f",
                "repositoryName": "my-super-code-repository"
            }
            ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
