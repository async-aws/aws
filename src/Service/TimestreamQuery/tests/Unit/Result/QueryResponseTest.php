<?php

namespace AsyncAws\TimestreamQuery\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\TimestreamQuery\Enum\ScalarType;
use AsyncAws\TimestreamQuery\Input\QueryRequest;
use AsyncAws\TimestreamQuery\Result\QueryResponse;
use AsyncAws\TimestreamQuery\TimestreamQueryClient;
use AsyncAws\TimestreamQuery\ValueObject\ColumnInfo;
use AsyncAws\TimestreamQuery\ValueObject\Datum;
use AsyncAws\TimestreamQuery\ValueObject\QueryStatus;
use AsyncAws\TimestreamQuery\ValueObject\Row;
use AsyncAws\TimestreamQuery\ValueObject\Type;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class QueryResponseTest extends TestCase
{
    public function testQueryResponse(): void
    {
        // see https://docs.aws.amazon.com/timestream/latest/developerguide/API_query_Query.html
        $response = new SimpleMockedResponse('{
            "ColumnInfo": [
                {
                    "Name": "foo",
                    "Type": {
                        "ScalarType": "VARCHAR"
                    }
                }
            ],
            "QueryId": "qwertyuiop",
            "QueryStatus": {
                "CumulativeBytesMetered": 1024,
                "CumulativeBytesScanned": 800,
                "ProgressPercentage": 1.0
            },
            "Rows": [
                {
                    "Data": [
                        {
                            "ScalarValue": "datum"
                        }
                    ]
                }
            ]
        }');

        $client = new MockHttpClient($response);
        $result = new QueryResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new TimestreamQueryClient(), new QueryRequest([]));

        self::assertCount(1, $result->getColumnInfo());
        self::assertInstanceOf(ColumnInfo::class, $result->getColumnInfo()[0]);
        self::assertSame('foo', $result->getColumnInfo()[0]->getName());
        self::assertInstanceOf(Type::class, $result->getColumnInfo()[0]->getType());
        self::assertSame(ScalarType::VARCHAR, $result->getColumnInfo()[0]->getType()->getScalarType());
        self::assertSame('qwertyuiop', $result->getQueryId());
        self::assertInstanceOf(QueryStatus::class, $result->getQueryStatus());
        self::assertSame(1024, $result->getQueryStatus()->getCumulativeBytesMetered());
        self::assertSame(800, $result->getQueryStatus()->getCumulativeBytesScanned());
        self::assertSame(1.0, $result->getQueryStatus()->getProgressPercentage());

        $rows = iterator_to_array($result->getRows(true));

        self::assertCount(1, $rows);
        self::assertInstanceOf(Row::class, $rows[0]);
        self::assertCount(1, $rows[0]->getData());
        self::assertInstanceOf(Datum::class, $rows[0]->getData()[0]);
        self::assertSame('datum', $rows[0]->getData()[0]->getScalarValue());
    }
}
