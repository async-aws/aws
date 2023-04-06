<?php

namespace AsyncAws\Athena\Tests\Unit\Result;

use AsyncAws\Athena\AthenaClient;
use AsyncAws\Athena\Input\ListNamedQueriesInput;
use AsyncAws\Athena\Result\ListNamedQueriesOutput;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListNamedQueriesOutputTest extends TestCase
{
    public function testListNamedQueriesOutput(): void
    {
        self::markTestSkipped('MissingAuthenticationTokenException, No yet Docker image for Athena ');

        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_ListNamedQueries.html
        $response = new SimpleMockedResponse('{
           "NamedQueryIds": [ "query-125", "query-145" ],
           "NextToken": "iad-tok1n253"
        }');

        $client = new MockHttpClient($response);
        $result = new ListNamedQueriesOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new AthenaClient(), new ListNamedQueriesInput([]));

        self::assertIsIterable($result->getNamedQueryIds());
        self::assertCount(2, $result->getNamedQueryIds());
        self::assertSame('iad-tok1n253', $result->getNextToken());
    }
}
