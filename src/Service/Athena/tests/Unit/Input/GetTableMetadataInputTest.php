<?php

namespace AsyncAws\Athena\Tests\Unit\Input;

use AsyncAws\Athena\Input\GetTableMetadataInput;
use AsyncAws\Core\Test\TestCase;

class GetTableMetadataInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetTableMetadataInput([
            'CatalogName' => 'myCatalogIad',
            'DatabaseName' => 'myDatabaseIad',
            'TableName' => 'myTableIad',
        ]);

        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_GetTableMetadata.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: AmazonAthena.GetTableMetadata
            Accept: application/json

            {
             "CatalogName": "myCatalogIad",
             "DatabaseName": "myDatabaseIad",
             "TableName": "myTableIad"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
