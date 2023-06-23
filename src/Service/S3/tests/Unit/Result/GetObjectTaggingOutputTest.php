<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Result\GetObjectTaggingOutput;
use AsyncAws\S3\ValueObject\Tag;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetObjectTaggingOutputTest extends TestCase
{
    public function testGetObjectTaggingOutput(): void
    {
        $response = new SimpleMockedResponse(
            '<?xml version="1.0" encoding="UTF-8"?>
<Tagging>
    <TagSet>
        <Tag>
            <Key>Key4</Key>
            <Value>Value4</Value>
        </Tag>
        <Tag>
            <Key>Key3</Key>
            <Value>Value3</Value>
        </Tag>
    </TagSet>
</Tagging>',
            ['x-amz-version-id' => 'ydlaNkwWm0SfKJR.T1b1fIdPRbldTYRI']
        );

        $client = new MockHttpClient($response);
        $result = new GetObjectTaggingOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('ydlaNkwWm0SfKJR.T1b1fIdPRbldTYRI', $result->getVersionId());
        self::assertEquals(
            [
                Tag::create(['Key' => 'Key4', 'Value' => 'Value4']),
                Tag::create(['Key' => 'Key3', 'Value' => 'Value3']),
            ],
            $result->getTagSet()
        );
    }

    public function testGetObjectTaggingOutputWithoutVersion(): void
    {
        $response = new SimpleMockedResponse(
            '<?xml version="1.0" encoding="UTF-8"?>
<Tagging>
    <TagSet>
        <Tag>
            <Key>Key4</Key>
            <Value>Value4</Value>
        </Tag>
        <Tag>
            <Key>Key3</Key>
            <Value>Value3</Value>
        </Tag>
    </TagSet>
</Tagging>');

        $client = new MockHttpClient($response);
        $result = new GetObjectTaggingOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));
        $result->resolve();

        self::assertNull($result->getVersionId());
        self::assertEquals(
            [
                Tag::create(['Key' => 'Key4', 'Value' => 'Value4']),
                Tag::create(['Key' => 'Key3', 'Value' => 'Value3']),
            ],
            $result->getTagSet()
        );
    }
}
