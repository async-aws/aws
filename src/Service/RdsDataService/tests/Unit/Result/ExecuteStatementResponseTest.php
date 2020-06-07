<?php

namespace AsyncAws\RdsDataService\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\RdsDataService\Result\ExecuteStatementResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ExecuteStatementResponseTest extends TestCase
{
    public function testExecuteStatementResponse(): void
    {
        // see https://docs.aws.amazon.com/rdsdataservice/latest/APIReference/API_ExecuteStatement.html
        $response = new SimpleMockedResponse('{
           "columnMetadata": [
              {
                 "arrayBaseColumnType": 1,
                 "isAutoIncrement": false,
                 "isCaseSensitive": false,
                 "isCurrency": false,
                 "isSigned": false,
                 "label": "name",
                 "name": "name",
                 "nullable": 0,
                 "precision": 0,
                 "scale": 0,
                 "schemaName": "name",
                 "tableName": "my_table",
                 "type": 0,
                 "typeName": "string"
              }
           ],
           "generatedFields": [
              {
                 "longValue": 123456789
              }
           ],
           "numberOfRecordsUpdated": 1337,
           "records": [
              [
                 {
                    "stringValue": "hello"
                 }
              ]
           ]
        }');

        $client = new MockHttpClient($response);
        $result = new ExecuteStatementResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertCount(1, $result->getColumnMetadata());
        self::assertSame(1, $result->getColumnMetadata()[0]->getArrayBaseColumnType());
        self::assertSame(false, $result->getColumnMetadata()[0]->getIsAutoIncrement());
        self::assertSame(false, $result->getColumnMetadata()[0]->getIsCaseSensitive());
        self::assertSame(false, $result->getColumnMetadata()[0]->getIsCurrency());
        self::assertSame(false, $result->getColumnMetadata()[0]->getIsSigned());
        self::assertSame('name', $result->getColumnMetadata()[0]->getLabel());
        self::assertSame('name', $result->getColumnMetadata()[0]->getName());
        self::assertSame(0, $result->getColumnMetadata()[0]->getNullable());
        self::assertSame(0, $result->getColumnMetadata()[0]->getPrecision());
        self::assertSame(0, $result->getColumnMetadata()[0]->getScale());
        self::assertSame('name', $result->getColumnMetadata()[0]->getSchemaName());
        self::assertSame('my_table', $result->getColumnMetadata()[0]->getTableName());
        self::assertSame(0, $result->getColumnMetadata()[0]->getType());
        self::assertSame('string', $result->getColumnMetadata()[0]->getTypeName());

        self::assertCount(1, $result->getgeneratedFields());
        self::assertSame('123456789', $result->getgeneratedFields()[0]->getLongValue());

        self::assertSame('1337', $result->getNumberOfRecordsUpdated()); // TODO this should be a number

        self::assertCount(1, $result->getRecords());
        self::assertCount(1, $result->getRecords()[0]);
        self::assertSame('hello', $result->getRecords()[0][0]->getStringValue());
    }
}
