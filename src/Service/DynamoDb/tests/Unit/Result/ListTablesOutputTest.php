<?php

namespace AsyncAws\DynamoDb\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\Result\ListTablesOutput;
use Symfony\Component\HttpClient\MockHttpClient;

class ListTablesOutputTest extends TestCase
{
    public function testListTablesOutput(): void
    {

        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "TableNames": [
                "Forum",
                "ProductCatalog",
                "Reply",
                "Thread"
            ]
        }');

        $client = new MockHttpClient($response);
        $result = new ListTablesOutput($client->request('POST', 'http://localhost'), $client);

        $tableNames = $result->getTableNames(true);
        foreach ($tableNames as $name) {
            self::assertEquals('Forum', $name);

            break;
        }
        self::assertCount(4, $tableNames);
        self::assertNull($result->getLastEvaluatedTableName());
    }
}
