<?php

namespace AsyncAws\RDSDataService\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\RDSDataService\Input\BeginTransactionRequest;

class BeginTransactionRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new BeginTransactionRequest([
            'database' => 'my_database',
            'resourceArn' => 'arn:resource',
            'schema' => 'schema',
            'secretArn' => 'arn:secret',
        ]);

        // see https://docs.aws.amazon.com/rdsdataservice/latest/APIReference/API_BeginTransaction.html
        $expected = '
            POST /BeginTransaction HTTP/1.0
            Content-Type: application/json

            {
               "database": "my_database",
               "resourceArn": "arn:resource",
               "schema": "schema",
               "secretArn": "arn:secret"
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
