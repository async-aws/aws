<?php

namespace AsyncAws\Athena\Tests\Unit\Result;

use AsyncAws\Athena\Result\GetTableMetadataOutput;
use AsyncAws\Athena\ValueObject\TableMetadata;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetTableMetadataOutputTest extends TestCase
{
    public function testGetTableMetadataOutput(): void
    {
        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_GetTableMetadata.html
        $response = new SimpleMockedResponse('{
           "TableMetadata": {
              "Columns": [
                 {
                    "Comment": "country name",
                    "Name": "country",
                    "Type": "string"
                 }
              ],
              "CreateTime": 1680938062,
              "LastAccessTime": 1680938065,
              "Name": "iad",
              "Parameters": {
                 "contrib" : "iad"
              },
              "PartitionKeys": [
                 {
                    "Comment": "new partition",
                    "Name": "iad",
                    "Type": "string"
                 }
              ],
              "TableType": "EXTERNAL_TABLE"
           }
        }');

        $client = new MockHttpClient($response);
        $result = new GetTableMetadataOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertInstanceOf(TableMetadata::class, $result->getTableMetadata());
        self::assertIsArray($result->getTableMetadata()->getPartitionKeys());
        self::assertSame('EXTERNAL_TABLE', $result->getTableMetadata()->getTableType());
    }
}
