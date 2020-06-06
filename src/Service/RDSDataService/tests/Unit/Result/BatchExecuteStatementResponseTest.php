<?php

namespace AsyncAws\RDSDataService\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\RDSDataService\Result\BatchExecuteStatementResponse;
use AsyncAws\RDSDataService\ValueObject\UpdateResult;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class BatchExecuteStatementResponseTest extends TestCase
{
    public function testBatchExecuteStatementResponse(): void
    {
        // see https://docs.aws.amazon.com/rdsdataservice/latest/APIReference/API_BatchExecuteStatement.html
        $response = new SimpleMockedResponse('{
           "updateResults": [
              {
                 "generatedFields": [
                    {
                       "stringValue": "string"
                    }
                 ]
              }
           ]
        }');

        $client = new MockHttpClient($response);
        $result = new BatchExecuteStatementResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertCount(1, $result->getUpdateResults());
        self::assertInstanceOf(UpdateResult::class, $result->getUpdateResults()[0]);
        self::assertSame([['stringValue' => 'string']], $result->getUpdateResults()[0]->getGeneratedFields());
    }
}
