<?php

namespace AsyncAws\Athena\Tests\Unit\Input;

use AsyncAws\Athena\Input\ListTableMetadataInput;
use AsyncAws\Core\Test\TestCase;

class ListTableMetadataInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListTableMetadataInput([
            'CatalogName' => 'iadCatalog',
            'DatabaseName' => 'iadDb',
            'Expression' => 'iad international',
            'NextToken' => 'iad-4582',
            'MaxResults' => 1337,
        ]);

        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_ListTableMetadata.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
x-amz-target: AmazonAthena.ListTableMetadata
Accept: application/json

{
"CatalogName": "iadCatalog",
"DatabaseName": "iadDb",
"Expression": "iad international",
"NextToken": "iad-4582",
"MaxResults": 1337
}
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
