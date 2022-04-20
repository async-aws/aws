<?php

namespace AsyncAws\CodeCommit\Tests\Unit\Result;

use AsyncAws\CodeCommit\Result\GetBranchOutput;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetBranchOutputTest extends TestCase
{
    public function testGetBranchOutput(): void
    {
        // see https://docs.aws.amazon.com/codecommit/latest/APIReference/API_GetBranch.html
        $response = new SimpleMockedResponse('{
               "branch": {
                  "branchName": "main",
                  "commitId": "abc123"
               }
        }');

        $client = new MockHttpClient($response);
        $result = new GetBranchOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('abc123', $result->getbranch()->getCommitId());
        self::assertSame('main', $result->getbranch()->getBranchName());
    }
}
