<?php

namespace AsyncAws\CodeCommit\Tests\Unit\Result;

use AsyncAws\CodeCommit\Result\GetCommitOutput;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetCommitOutputTest extends TestCase
{
    public function testGetCommitOutput(): void
    {
        // see https://docs.aws.amazon.com/codecommit/latest/APIReference/API_GetCommit.html
        $response = new SimpleMockedResponse('{
            "commit": {
                "commitId": "12345678EXAMPLE",
                "additionalData": "",
                "committer": {
                    "date": "1484167798 -0800",
                    "name": "Mary Major",
                    "email": "mary_major@example.com"
                },
                "author": {
                    "date": "1484167798 -0800",
                    "name": "Mary Major",
                    "email": "mary_major@example.com"
                },
                "treeId": "347a3408EXAMPLE",
                "parents": [
                    "7aa87a0EXAMPLE"
                ],
                "message": "Fix incorrect variable name\n"
            }
        }');

        $client = new MockHttpClient($response);
        $result = new GetCommitOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('12345678EXAMPLE', $result->getCommit()->getCommitId());
        self::assertSame('', $result->getCommit()->getAdditionalData());
        self::assertSame('1484167798 -0800', $result->getCommit()->getCommitter()->getDate());
        self::assertSame('Mary Major', $result->getCommit()->getCommitter()->getName());
        self::assertSame('mary_major@example.com', $result->getCommit()->getCommitter()->getEmail());
        self::assertSame('1484167798 -0800', $result->getCommit()->getAuthor()->getDate());
        self::assertSame('Mary Major', $result->getCommit()->getAuthor()->getName());
        self::assertSame('mary_major@example.com', $result->getCommit()->getAuthor()->getEmail());
        self::assertSame('347a3408EXAMPLE', $result->getCommit()->getTreeId());
        self::assertSame(['7aa87a0EXAMPLE'], $result->getCommit()->getParents());
        self::assertSame("Fix incorrect variable name\n", $result->getCommit()->getMessage());
    }
}
