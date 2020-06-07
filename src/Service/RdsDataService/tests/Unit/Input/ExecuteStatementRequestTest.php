<?php

namespace AsyncAws\RdsDataService\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\RdsDataService\Input\ExecuteStatementRequest;
use AsyncAws\RdsDataService\ValueObject\ResultSetOptions;
use AsyncAws\RdsDataService\ValueObject\SqlParameter;

class ExecuteStatementRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ExecuteStatementRequest([
            'continueAfterTimeout' => false,
            'database' => 'my_database',
            'includeResultMetadata' => true,
            'parameters' => [
                new SqlParameter(['name' => 'array', 'value' => ['arrayValue' => ['stringValues' => ['1', '2']]]]),
                new SqlParameter(['name' => 'blob', 'value' => ['blobValue' => '0123456789']]),
                new SqlParameter(['name' => 'boolean', 'value' => ['booleanValue' => true]]),
                new SqlParameter(['name' => 'double', 'value' => ['doubleValue' => 0.5]]),
                new SqlParameter(['name' => 'null', 'value' => ['isNull' => true]]),
                new SqlParameter(['name' => 'string', 'value' => ['longValue' => 123456789]]),
                new SqlParameter(['name' => 'struct', 'value' => ['stringValue' => 'Mustermann']]),
            ],
            'resourceArn' => 'arn:resource',
            'resultSetOptions' => new ResultSetOptions([
                'decimalReturnType' => 'STRING',
            ]),
            // https://docs.aws.amazon.com/rdsdataservice/latest/APIReference/API_ExecuteStatement.html#rdsdtataservice-ExecuteStatement-request-schema
            // 'schema' => 'change me', // currently unsupported
            'secretArn' => 'arn:secret',
            'sql' => 'SELECT * FROM users WHERE array = :array blob = :blob boolean = :boolean double = :double null = :null string = :string struct = :struct',
            'transactionId' => '0123456789',
        ]);

        // see https://docs.aws.amazon.com/rdsdataservice/latest/APIReference/API_ExecuteStatement.html
        $expected = '
            POST /Execute HTTP/1.0
            Content-Type: application/json

            {
               "continueAfterTimeout": false,
               "database": "my_database",
               "includeResultMetadata": true,
               "parameters": [
                  {"name": "array", "value": {"arrayValue": {"stringValues": ["1", "2"]}}},
                  {"name": "blob", "value": {"blobValue": "MDEyMzQ1Njc4OQ=="}},
                  {"name": "boolean", "value": {"booleanValue": true}},
                  {"name": "double", "value": {"doubleValue": 0.5}},
                  {"name": "null", "value": {"isNull": true}},
                  {"name": "string", "value": {"longValue": 123456789}},
                  {"name": "struct", "value": {"stringValue": "Mustermann"}}
               ],
               "resourceArn": "arn:resource",
               "resultSetOptions": {
                  "decimalReturnType": "STRING"
               },
               "secretArn": "arn:secret",
               "sql": "SELECT * FROM users WHERE array = :array blob = :blob boolean = :boolean double = :double null = :null string = :string struct = :struct",
               "transactionId": "0123456789"
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
