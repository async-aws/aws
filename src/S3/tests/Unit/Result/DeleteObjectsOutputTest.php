<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Result\DeleteObjectsOutput;
use Symfony\Component\HttpClient\MockHttpClient;

class DeleteObjectsOutputTest extends TestCase
{
    public function testDeleteObjectsOutput(): void
    {
        self::markTestIncomplete('Not implemented');

        // see example-1.json from SDK
        $response = new SimpleMockedResponse('<Deleted>
          <key_0>
            <DeleteMarker>true</DeleteMarker>
            <DeleteMarkerVersionId>A._w1z6EFiCF5uhtQMDal9JDkID9tQ7F</DeleteMarkerVersionId>
            <Key>objectkey1</Key>
          </key_0>
          <key_1>
            <DeleteMarker>true</DeleteMarker>
            <DeleteMarkerVersionId>iOd_ORxhkKe_e8G8_oSGxt2PjsCZKlkt</DeleteMarkerVersionId>
            <Key>objectkey2</Key>
          </key_1>
        </Deleted>');

        $client = new MockHttpClient($response);
        $result = new DeleteObjectsOutput($client->request('POST', 'http://localhost'), $client);

        // self::assertTODO(expected, $result->getDeleted());
        self::assertSame('changeIt', $result->getRequestCharged());
        // self::assertTODO(expected, $result->getErrors());
    }
}
