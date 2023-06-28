<?php

namespace AsyncAws\Athena\Tests\Unit\Result;

use AsyncAws\Athena\AthenaClient;
use AsyncAws\Athena\Input\GetQueryResultsInput;
use AsyncAws\Athena\Result\GetQueryResultsOutput;
use AsyncAws\Athena\ValueObject\Datum;
use AsyncAws\Athena\ValueObject\ResultSet;
use AsyncAws\Athena\ValueObject\ResultSetMetadata;
use AsyncAws\Athena\ValueObject\Row;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetQueryResultsOutputTest extends TestCase
{
    public function testGetQueryResultsOutput(): void
    {
        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_GetQueryResults.html
        $response = new SimpleMockedResponse('{
    "NextToken": "iad-token-236",
    "ResultSet": {
    "ResultSetMetadata": {
       "ColumnInfo": [
          {
            "CatalogName": "hive",
            "SchemaName": "",
            "TableName": "",
            "Name": "date",
            "Label": "date",
            "Type": "date",
            "Precision": 0,
            "Scale": 0,
            "Nullable": "UNKNOWN",
            "CaseSensitive": false
          },
          {
            "CatalogName": "hive",
            "SchemaName": "",
            "TableName": "",
            "Name": "browser",
            "Label": "browser",
            "Type": "varchar",
            "Precision": 2147483647,
            "Scale": 0,
            "Nullable": "UNKNOWN",
            "CaseSensitive": true
          }
       ]
    },
    "Rows": [
       {
          "Data": [
             {
                "VarCharValue": "date"
              },
              {
                "VarCharValue": "browser"
              }
          ]
       },
       {
            "Data": [
                {
                  "VarCharValue": "2014-07-05"
                },
                {
                  "VarCharValue": "Safari"
                }
            ]
        }
    ]
    },
    "UpdateCount": 1337
}');

        $client = new MockHttpClient($response);
        $result = new GetQueryResultsOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new AthenaClient(), new GetQueryResultsInput([]));

        self::assertSame(1337, $result->getUpdateCount());
        self::assertInstanceOf(ResultSet::class, $result->getResultSet());
        self::assertCount(2, $result->getResultSet()->getRows());
        self::assertInstanceOf(Row::class, $result->getResultSet()->getRows()[0]);
        self::assertInstanceOf(Datum::class, $result->getResultSet()->getRows()[0]->getData()[0]);
        self::assertInstanceOf(ResultSetMetadata::class, $result->getResultSet()->getResultSetMetadata());
        self::assertCount(2, $result->getResultSet()->getResultSetMetadata()->getColumnInfo());
        self::assertSame('iad-token-236', $result->getNextToken());
    }
}
