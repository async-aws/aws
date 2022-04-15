<?php

namespace AsyncAws\TimestreamQuery\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\TimestreamQuery\Result\CancelQueryResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CancelQueryResponseTest extends TestCase
{
    public function testCancelQueryResponse(): void
    {
        // see https://docs.aws.amazon.com/timestream/latest/developerguide/API_query_CancelQuery.html
        $response = new SimpleMockedResponse('{
            "CancellationMessage": "Query cancelled."
        }');

        $client = new MockHttpClient($response);
        $result = new CancelQueryResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('Query cancelled.', $result->getCancellationMessage());
    }
}
