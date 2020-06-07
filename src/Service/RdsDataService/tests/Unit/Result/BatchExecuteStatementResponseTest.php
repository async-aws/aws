<?php

namespace AsyncAws\RdsDataService\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\RdsDataService\Result\BatchExecuteStatementResponse;
use AsyncAws\RdsDataService\ValueObject\UpdateResult;
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
        self::assertCount(1, $result->getUpdateResults()[0]->getGeneratedFields());
        self::assertSame('string', $result->getUpdateResults()[0]->getGeneratedFields()[0]->getStringValue());
    }
}
