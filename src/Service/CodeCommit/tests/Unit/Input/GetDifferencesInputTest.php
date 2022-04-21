<?php

namespace AsyncAws\CodeCommit\Tests\Unit\Input;

use AsyncAws\CodeCommit\Input\GetDifferencesInput;
use AsyncAws\Core\Test\TestCase;

class GetDifferencesInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetDifferencesInput([
            'repositoryName' => 'MyFirstRepository',
            'beforeCommitSpecifier' => 'abc123',
            'afterCommitSpecifier' => 'xyz789',
            'beforePath' => '/src/Service/CodeCommit/README.md',
            'afterPath' => '/src/Service/CodeCommit/composer.json',
            'MaxResults' => 1337,
            'NextToken' => 'NEXT_TOK',
        ]);

        // see https://docs.aws.amazon.com/codecommit/latest/APIReference/API_GetDifferences.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: CodeCommit_20150413.GetDifferences

            {
            "MaxResults": 1337,
            "NextToken": "NEXT_TOK",
            "afterCommitSpecifier": "xyz789",
            "beforeCommitSpecifier": "abc123",
            "afterPath": "/src/Service/CodeCommit/composer.json",
            "beforePath": "/src/Service/CodeCommit/README.md",
            "repositoryName": "MyFirstRepository"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
