<?php

namespace AsyncAws\RdsDataService\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\RdsDataService\Input\BatchExecuteStatementRequest;
use AsyncAws\RdsDataService\ValueObject\SqlParameter;

class BatchExecuteStatementRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new BatchExecuteStatementRequest([
            'database' => 'my_database',
            'parameterSets' => [
                [
                    new SqlParameter(['name' => 'z', 'value' => ['stringValue' => 'key']]),
                ],
            ],
            'resourceArn' => 'arn:resource',
            'schema' => 'schema',
            'secretArn' => 'arn:secret',
            'sql' => 'SELECT * FROM x WHERE y = :z',
            'transactionId' => 'transaction',
        ]);

        // see https://docs.aws.amazon.com/rdsdataservice/latest/APIReference/API_BatchExecuteStatement.html
        $expected = '
            POST /BatchExecute HTTP/1.0
            Content-Type: application/json

            {
               "database": "my_database",
               "parameterSets": [
                  [
                     {
                        "name": "z",
                        "value": {
                           "stringValue": "key"
                        }
                     }
                  ]
               ],
               "resourceArn": "arn:resource",
               "schema": "schema",
               "secretArn": "arn:secret",
               "sql": "SELECT * FROM x WHERE y = :z",
               "transactionId": "transaction"
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
