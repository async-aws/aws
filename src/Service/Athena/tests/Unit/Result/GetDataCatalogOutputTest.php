<?php

namespace AsyncAws\Athena\Tests\Unit\Result;

use AsyncAws\Athena\Result\GetDataCatalogOutput;
use AsyncAws\Athena\ValueObject\DataCatalog;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetDataCatalogOutputTest extends TestCase
{
    public function testGetDataCatalogOutput(): void
    {
        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_GetDataCatalog.html
        $response = new SimpleMockedResponse('{
   "DataCatalog": {
      "Description": "iad international async_aws",
      "Name": "I@d-2023",
      "Parameters": {
         "contrib" : "iad"
      },
      "Type": "GLUE"
   }
}');

        $client = new MockHttpClient($response);
        $result = new GetDataCatalogOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertInstanceOf(DataCatalog::class, $result->getDataCatalog());
        self::assertSame('GLUE', $result->getDataCatalog()->getType());
    }
}
