<?php

namespace AsyncAws\Athena\Tests\Unit\Result;

use AsyncAws\Athena\AthenaClient;
use AsyncAws\Athena\Input\ListTableMetadataInput;
use AsyncAws\Athena\Result\ListTableMetadataOutput;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListTableMetadataOutputTest extends TestCase
{
    public function testListTableMetadataOutput(): void
    {
        self::markTestSkipped('MissingAuthenticationTokenException, No yet Docker image for Athena ');
        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_ListTableMetadata.html
        $response = new SimpleMockedResponse('{
           "NextToken": "iad-tok1n256",
           "TableMetadataList": [
              {
                 "Columns": [
                    {
                       "Comment": "product name",
                       "Name": "product_name",
                       "Type": "string"
                    }
                 ],
                 "CreateTime": 1680943083.125,
                 "LastAccessTime": 1680943085.025,
                 "Name": "Catalog",
                 "Parameters": {
                    "contrib" : "iad"
                 },
                 "PartitionKeys": [
                    {
                       "Comment": "new one",
                       "Name": "stagging",
                       "Type": "string"
                    }
                 ],
                 "TableType": "EXTERNAL_TABLE"
              }
           ]
        }');

        $client = new MockHttpClient($response);
        $result = new ListTableMetadataOutput(
            new Response(
                $client->request(
                    'POST',
                    'http://localhost'),
                $client,
                new NullLogger()),
            new AthenaClient(),
            new ListTableMetadataInput([
                'CatalogName' => 'iadCatalog',
                'DatabaseName' => 'iadDatbase',
            ])
        );

        self::assertIsIterable($result->getTableMetadataList());
        self::assertCount(1, $result->getTableMetadataList());
        self::assertArrayHasKey('TableType', $result->getTableMetadataList());
        self::assertSame('iad-tok1n256', $result->getNextToken());
    }
}
