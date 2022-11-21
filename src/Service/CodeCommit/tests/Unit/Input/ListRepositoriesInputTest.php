<?php

namespace AsyncAws\CodeCommit\Tests\Unit\Input;

use AsyncAws\CodeCommit\Input\ListRepositoriesInput;
use AsyncAws\Core\Test\TestCase;

class ListRepositoriesInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListRepositoriesInput([
            'nextToken' => 'NEXT_TOK',
            'sortBy' => 'repositoryName',
            'order' => 'ascending',
        ]);

        // see https://docs.aws.amazon.com/codecommit/latest/APIReference/API_ListRepositories.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: CodeCommit_20150413.ListRepositories

            {
                "nextToken": "NEXT_TOK",
                "order": "ascending",
                "sortBy": "repositoryName"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
