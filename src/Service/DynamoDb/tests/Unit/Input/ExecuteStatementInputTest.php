<?php

namespace AsyncAws\DynamoDb\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\Input\ExecuteStatementInput;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;

class ExecuteStatementInputTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new ExecuteStatementInput([
            'Statement' => 'change me',
            'Parameters' => [new AttributeValue([
                'S' => 'change me',
                'N' => 'change me',
                'B' => 'change me',
                'SS' => ['change me'],
                'NS' => ['change me'],
                'BS' => ['change me'],
                'M' => ['change me' => ''],
                'L' => [''],
                'NULL' => false,
                'BOOL' => false,
            ])],
            'ConsistentRead' => false,
            'NextToken' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_ExecuteStatement.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.0

            {
            "change": "it"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
