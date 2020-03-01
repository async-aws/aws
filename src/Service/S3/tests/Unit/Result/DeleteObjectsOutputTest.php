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
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('<?xml version="1.0" encoding="UTF-8"?>
            <DeleteResult xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
                <Deleted>
                    <Key>sample1.txt</Key>
                </Deleted>
                <Error>
                    <Key>sample2.txt</Key>
                    <Code>AccessDenied</Code>
                    <Message>Access Denied</Message>
                </Error>
            </DeleteResult>',
            [
                'x-amz-id-2' => '5h4FxSNCUS7wP5z92eGCWDshNpMnRuXvETa4HH3LvvH6VAIr0jU7tH9kM7X+njXx',
                'x-amz-request-id' => 'A437B3B641629AEE',
                'x-amz-request-charged' => 'requester',
                'Date' => 'Fri, 02 Dec 2011 01:53:42 GMT',
                'Content-Type' => 'application/xml',
            ]
        );

        $client = new MockHttpClient($response);
        $result = new DeleteObjectsOutput($client->request('POST', 'http://localhost'), $client);

        self::assertCount(1, $result->getDeleted());
        self::assertEquals('sample1.txt', $result->getDeleted()[0]->getKey());

        self::assertCount(1, $result->getErrors());
        self::assertEquals('sample2.txt', $result->getErrors()[0]->getKey());
        self::assertEquals('AccessDenied', $result->getErrors()[0]->getCode());
        self::assertEquals('Access Denied', $result->getErrors()[0]->getMessage());
        self::assertEquals('requester', $result->getRequestCharged());
    }
}
