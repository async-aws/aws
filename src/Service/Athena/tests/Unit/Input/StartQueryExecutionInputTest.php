<?php

namespace AsyncAws\Athena\Tests\Unit\Input;

use AsyncAws\Athena\Input\StartQueryExecutionInput;
use AsyncAws\Athena\ValueObject\EncryptionConfiguration;
use AsyncAws\Athena\ValueObject\QueryExecutionContext;
use AsyncAws\Athena\ValueObject\ResultConfiguration;
use AsyncAws\Core\Test\TestCase;

class StartQueryExecutionInputTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new StartQueryExecutionInput([
            'QueryString' => 'change me',
            'ClientRequestToken' => 'change me',
            'QueryExecutionContext' => new QueryExecutionContext([
                'Database' => 'change me',
                'Catalog' => 'change me',
            ]),
            'ResultConfiguration' => new ResultConfiguration([
                'OutputLocation' => 'change me',
                'EncryptionConfiguration' => new EncryptionConfiguration([
                    'EncryptionOption' => 'change me',
                    'KmsKey' => 'change me',
                ]),
            ]),
            'WorkGroup' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/athena/latest/APIReference/Welcome.html/API_StartQueryExecution.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1

            {
            "change": "it"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
