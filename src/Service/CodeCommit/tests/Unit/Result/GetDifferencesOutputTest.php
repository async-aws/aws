<?php

namespace AsyncAws\CodeCommit\Tests\Unit\Result;

use AsyncAws\CodeCommit\CodeCommitClient;
use AsyncAws\CodeCommit\Input\GetDifferencesInput;
use AsyncAws\CodeCommit\Result\GetDifferencesOutput;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetDifferencesOutputTest extends TestCase
{
    public function testGetDifferencesOutput(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/codecommit/latest/APIReference/API_GetDifferences.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new GetDifferencesOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new CodeCommitClient(), new GetDifferencesInput([]));

        // self::assertTODO(expected, $result->getdifferences());
        self::assertSame('changeIt', $result->getNextToken());
    }
}
