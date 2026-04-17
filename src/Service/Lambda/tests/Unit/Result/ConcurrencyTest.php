<?php

namespace AsyncAws\Lambda\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Result\Concurrency;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ConcurrencyTest extends TestCase
{
    public function testConcurrency(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "ReservedConcurrentExecutions": 100
        }');

        $client = new MockHttpClient($response);
        $result = new Concurrency(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame(100, $result->getReservedConcurrentExecutions());
    }
}
