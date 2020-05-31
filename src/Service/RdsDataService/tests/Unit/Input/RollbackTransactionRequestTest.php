<?php

namespace AsyncAws\RdsDataService\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\RdsDataService\Input\RollbackTransactionRequest;

class RollbackTransactionRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new RollbackTransactionRequest([
            'resourceArn' => 'arn:resource',
            'secretArn' => 'arn:secret',
            'transactionId' => 'transaction',
        ]);

        // see https://docs.aws.amazon.com/rdsdataservice/latest/APIReference/API_RollbackTransaction.html
        $expected = '
            POST /RollbackTransaction HTTP/1.0
            Content-Type: application/json

            {
               "resourceArn": "arn:resource",
               "secretArn": "arn:secret",
               "transactionId": "transaction"
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
