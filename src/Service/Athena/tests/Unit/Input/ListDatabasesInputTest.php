<?php

namespace AsyncAws\Athena\Tests\Unit\Input;

use AsyncAws\Athena\Input\ListDatabasesInput;
use AsyncAws\Core\Test\TestCase;

class ListDatabasesInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListDatabasesInput([
            'CatalogName' => 'IadCatalog',
            'NextToken' => 'iad-002',
            'MaxResults' => 1337,
        ]);

        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_ListDatabases.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: AmazonAthena.ListDatabases
            Accept: application/json

            {
    "CatalogName": "IadCatalog",
    "NextToken": "iad-002",
    "MaxResults": 1337
}
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
