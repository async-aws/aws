<?php

namespace AsyncAws\CodeCommit\Tests\Unit\Input;

use AsyncAws\CodeCommit\Input\DeleteRepositoryInput;
use AsyncAws\Core\Test\TestCase;

class DeleteRepositoryInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DeleteRepositoryInput([
            'repositoryName' => 'repo-i-no-longer-care-about',
        ]);

        // see https://docs.aws.amazon.com/codecommit/latest/APIReference/API_DeleteRepository.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: CodeCommit_20150413.DeleteRepository

            {
            "repositoryName": "repo-i-no-longer-care-about"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
