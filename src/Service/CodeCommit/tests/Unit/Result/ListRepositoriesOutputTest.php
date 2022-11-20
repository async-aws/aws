<?php

namespace AsyncAws\CodeCommit\Tests\Unit\Result;

use AsyncAws\CodeCommit\CodeCommitClient;
use AsyncAws\CodeCommit\Input\ListRepositoriesInput;
use AsyncAws\CodeCommit\Result\ListRepositoriesOutput;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListRepositoriesOutputTest extends TestCase
{
    public function testListRepositoriesOutput(): void
    {
        // see https://docs.aws.amazon.com/codecommit/latest/APIReference/API_ListRepositories.html
        $response = new SimpleMockedResponse('{
            "nextToken": "NEXT_TOK",
            "repositories":[
                {
                    "repositoryId": "f7579e13-b83e-4027-aaef-650c0EXAMPLE",
                    "repositoryName": "MyDemoRepo"
                },
                {
                    "repositoryId": "cfc29ac4-b0cb-44dc-9990-f6f51EXAMPLE",
                    "repositoryName": "MyOtherDemoRepo"
                }
            ]
            }');

        $client = new MockHttpClient($response);
        $result = new ListRepositoriesOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new CodeCommitClient(), new ListRepositoriesInput([]));

        $repos = iterator_to_array($result->getRepositories(true));

        self::assertSame('NEXT_TOK', $result->getnextToken());
        self::assertSame('f7579e13-b83e-4027-aaef-650c0EXAMPLE', $repos[0]->getRepositoryId());
        self::assertSame('MyDemoRepo', $repos[0]->getRepositoryName());
        self::assertSame('cfc29ac4-b0cb-44dc-9990-f6f51EXAMPLE', $repos[1]->getRepositoryId());
        self::assertSame('MyOtherDemoRepo', $repos[1]->getRepositoryName());
    }
}
