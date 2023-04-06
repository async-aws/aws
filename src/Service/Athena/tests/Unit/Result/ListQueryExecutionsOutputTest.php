<?php

namespace AsyncAws\Athena\Tests\Unit\Result;

use AsyncAws\Athena\AthenaClient;
use AsyncAws\Athena\Input\ListQueryExecutionsInput;
use AsyncAws\Athena\Result\ListQueryExecutionsOutput;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListQueryExecutionsOutputTest extends TestCase
{
    public function testListQueryExecutionsOutput(): void
    {
        self::markTestSkipped('MissingAuthenticationTokenException, No yet Docker image for Athena ');
        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_ListQueryExecutions.html
        $response = new SimpleMockedResponse('{
           "NextToken": "iad-tok1n255",
           "QueryExecutionIds": [ "query-001" ]
        }');

        $client = new MockHttpClient($response);
        $result = new ListQueryExecutionsOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new AthenaClient(), new ListQueryExecutionsInput([]));

        self::assertCount(1, $result->getQueryExecutionIds());
        self::assertSame('iad-tok1n255', $result->getNextToken());
    }
}
