<?php

namespace AsyncAws\Athena\Tests\Unit\Input;

use AsyncAws\Athena\Input\GetDataCatalogInput;
use AsyncAws\Core\Test\TestCase;

class GetDataCatalogInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetDataCatalogInput([
            'Name' => 'myDataCatalog',
        ]);

        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_GetDataCatalog.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
x-amz-target: AmazonAthena.GetDataCatalog

{ "Name": "myDataCatalog" }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
