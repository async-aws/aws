<?php

namespace AsyncAws\Kinesis\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Result\ListTagsForStreamOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListTagsForStreamOutputTest extends TestCase
{
    public function testListTagsForStreamOutput(): void
    {
        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_ListTagsForStream.html
        $response = new SimpleMockedResponse('{
  "HasMoreTags": "false",
  "Tags" : [
     {
       "Key": "Project",
       "Value": "myProject"
     },
     {
       "Key": "Environment",
       "Value": "Production"
     }
   ]
}');

        $client = new MockHttpClient($response);
        $result = new ListTagsForStreamOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertCount(2, $result->getTags());
        self::assertSame('Project', $result->getTags()[0]->getKey());
        self::assertFalse($result->getHasMoreTags());
    }
}
