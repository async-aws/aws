<?php

namespace AsyncAws\CodeCommit\Tests\Unit\Input;

use AsyncAws\CodeCommit\Input\GetDifferencesInput;
use AsyncAws\Core\Test\TestCase;

class GetDifferencesInputTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new GetDifferencesInput([
            'repositoryName' => 'change me',
            'beforeCommitSpecifier' => 'change me',
            'afterCommitSpecifier' => 'change me',
            'beforePath' => 'change me',
            'afterPath' => 'change me',
            'MaxResults' => 1337,
            'NextToken' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/codecommit/latest/APIReference/API_GetDifferences.html
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
