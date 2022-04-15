<?php

namespace AsyncAws\TimestreamQuery\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\TimestreamQuery\Enum\ScalarType;
use AsyncAws\TimestreamQuery\Result\PrepareQueryResponse;
use AsyncAws\TimestreamQuery\ValueObject\SelectColumn;
use AsyncAws\TimestreamQuery\ValueObject\Type;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class PrepareQueryResponseTest extends TestCase
{
    public function testPrepareQueryResponse(): void
    {
        // see https://docs.aws.amazon.com/timestream/latest/developerguide/API_query_PrepareQuery.html
        $response = new SimpleMockedResponse('{
            "Columns": [
                {
                    "Aliased": false,
                    "DatabaseName": "db",
                    "Name": "foo",
                    "TableName": "tbl",
                    "Type": {
                        "ScalarType": "VARCHAR"
                    }
                }
            ],
            "Parameters": [],
            "QueryString": "query string"
        }');

        $client = new MockHttpClient($response);
        $result = new PrepareQueryResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertCount(1, $result->getColumns());
        self::assertInstanceOf(SelectColumn::class, $result->getColumns()[0]);
        self::assertFalse($result->getColumns()[0]->getAliased());
        self::assertSame('db', $result->getColumns()[0]->getDatabaseName());
        self::assertSame('foo', $result->getColumns()[0]->getName());
        self::assertSame('tbl', $result->getColumns()[0]->getTableName());
        self::assertInstanceOf(Type::class, $result->getColumns()[0]->getType());
        self::assertSame(ScalarType::VARCHAR, $result->getColumns()[0]->getType()->getScalarType());
        self::assertSame([], $result->getParameters());
        self::assertSame('query string', $result->getQueryString());
    }
}
