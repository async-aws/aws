<?php

namespace AsyncAws\RdsDataService\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\RdsDataService\Result\CommitTransactionResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CommitTransactionResponseTest extends TestCase
{
    public function testCommitTransactionResponse(): void
    {
        // see https://docs.aws.amazon.com/rdsdataservice/latest/APIReference/API_CommitTransaction.html
        $response = new SimpleMockedResponse('{
           "transactionStatus": "done"
        }');

        $client = new MockHttpClient($response);
        $result = new CommitTransactionResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('done', $result->gettransactionStatus());
    }
}
