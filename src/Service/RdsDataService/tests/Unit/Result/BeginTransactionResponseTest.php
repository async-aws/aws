<?php

namespace AsyncAws\RdsDataService\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\RdsDataService\Result\BeginTransactionResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class BeginTransactionResponseTest extends TestCase
{
    public function testBeginTransactionResponse(): void
    {
        // see https://docs.aws.amazon.com/rdsdataservice/latest/APIReference/API_BeginTransaction.html
        $response = new SimpleMockedResponse('{
           "transactionId": "transaction"
        }');

        $client = new MockHttpClient($response);
        $result = new BeginTransactionResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('transaction', $result->gettransactionId());
    }
}
