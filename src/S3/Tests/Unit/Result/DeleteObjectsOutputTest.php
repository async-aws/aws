<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\S3\Result\DeleteObjectsOutput;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class DeleteObjectsOutputTest extends TestCase
{
    public function testDeleteObjectsOutput(): void
    {
        self::markTestIncomplete('Not implemented');

        $response = new SimpleMockedResponse('<?xml version="1.0" encoding="UTF-8"?>
            <ChangeIt/>
        ');

        $client = new MockHttpClient($response);
        $result = new DeleteObjectsOutput($client->request('POST', 'http://localhost'), $client);

        // self::assertTODO(expected, $result->getDeleted());
        self::assertStringContainsString('change it', $result->getRequestCharged());
        // self::assertTODO(expected, $result->getErrors());
    }
}
