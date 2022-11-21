<?php

namespace AsyncAws\CodeCommit\Tests\Unit\Input;

use AsyncAws\CodeCommit\Input\CreateRepositoryInput;
use AsyncAws\Core\Test\TestCase;

class CreateRepositoryInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CreateRepositoryInput([
            'repositoryName' => 'billion-dollar-business-idea-repo',
            'repositoryDescription' => 'this is the project that will finally make me rich!',
            'tags' => ['some-tag' => 'some value'],
        ]);

        // see https://docs.aws.amazon.com/codecommit/latest/APIReference/API_CreateRepository.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: CodeCommit_20150413.CreateRepository

            {
            "repositoryDescription": "this is the project that will finally make me rich!",
            "repositoryName": "billion-dollar-business-idea-repo",
            "tags": {
                "some-tag": "some value"
            }
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
