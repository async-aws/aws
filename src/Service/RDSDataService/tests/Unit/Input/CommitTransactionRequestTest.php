<?php

namespace AsyncAws\RDSDataService\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\RDSDataService\Input\CommitTransactionRequest;

class CommitTransactionRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CommitTransactionRequest([
            'resourceArn' => 'arn:resource',
            'secretArn' => 'arn:secret',
            'transactionId' => 'transaction',
        ]);

        // see https://docs.aws.amazon.com/rdsdataservice/latest/APIReference/API_CommitTransaction.html
        $expected = '
            POST /CommitTransaction HTTP/1.0
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
