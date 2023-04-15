<?php

namespace AsyncAws\Athena\Tests\Unit\Input;

use AsyncAws\Athena\Input\GetDatabaseInput;
use AsyncAws\Core\Test\TestCase;

class GetDatabaseInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetDatabaseInput([
            'CatalogName' => 'my_catalog_name',
            'DatabaseName' => 'my_database_name',
        ]);

        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_GetDatabase.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: AmazonAthena.GetDatabase

            {
            "CatalogName": "my_catalog_name",
            "DatabaseName": "my_database_name"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
