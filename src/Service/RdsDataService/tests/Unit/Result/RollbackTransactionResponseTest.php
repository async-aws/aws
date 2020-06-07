<?php

namespace AsyncAws\RdsDataService\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\RdsDataService\Result\RollbackTransactionResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class RollbackTransactionResponseTest extends TestCase
{
    public function testRollbackTransactionResponse(): void
    {
        // see https://docs.aws.amazon.com/rdsdataservice/latest/APIReference/API_RollbackTransaction.html
        $response = new SimpleMockedResponse('{
           "transactionStatus": "done"
        }');

        $client = new MockHttpClient($response);
        $result = new RollbackTransactionResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('done', $result->gettransactionStatus());
    }
}
