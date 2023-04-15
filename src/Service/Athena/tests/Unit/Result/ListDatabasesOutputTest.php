<?php

namespace AsyncAws\Athena\Tests\Unit\Result;

use AsyncAws\Athena\AthenaClient;
use AsyncAws\Athena\Input\ListDatabasesInput;
use AsyncAws\Athena\Result\ListDatabasesOutput;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListDatabasesOutputTest extends TestCase
{
    public function testListDatabasesOutput(): void
    {
        self::markTestSkipped('MissingAuthenticationTokenException, No yet Docker image for Athena ');
        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_ListDatabases.html
        $response = new SimpleMockedResponse('{
           "DatabaseList": [
              {
                 "Description": "iad catalog table",
                 "Name": "catalog",
                 "Parameters": {
                    "contrib" : "iad"
                 }
              }
           ],
           "NextToken": "iad-tok1n253"
        }');

        $client = new MockHttpClient($response);
        $result = new ListDatabasesOutput(
            new Response(
                $client->request('POST', 'http://localhost'), $client, new NullLogger()
            ),
            new AthenaClient(),
            new ListDatabasesInput([
                'CatalogName' => 'iadCatalog',
            ])
        );

        self::assertIsIterable($result->getDatabaseList());
        self::assertCount(1, $result->getDatabaseList());
        self::assertSame('iad-tok1n253', $result->getNextToken());
    }
}
