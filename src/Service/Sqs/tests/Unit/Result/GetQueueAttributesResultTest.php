<?php

declare(strict_types=1);

namespace AsyncAws\Sqs\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Sqs\Result\GetQueueAttributesResult;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetQueueAttributesResultTest extends TestCase
{
    public function testGetQueueAttributesResult()
    {
        $response = new SimpleMockedResponse(<<<XML
<GetQueueAttributesResponse>
  <GetQueueAttributesResult>
    <Attribute>
      <Name>ReceiveMessageWaitTimeSeconds</Name>
      <Value>2</Value>
    </Attribute>
    <Attribute>
      <Name>VisibilityTimeout</Name>
      <Value>30</Value>
    </Attribute>
    <Attribute>
      <Name>ApproximateNumberOfMessages</Name>
      <Value>0</Value>
    </Attribute>
    <Attribute>
      <Name>ApproximateNumberOfMessagesNotVisible</Name>
      <Value>0</Value>
    </Attribute>
    <Attribute>
      <Name>CreatedTimestamp</Name>
      <Value>1286771522</Value>
    </Attribute>
    <Attribute>
      <Name>LastModifiedTimestamp</Name>
      <Value>1286771522</Value>
    </Attribute>
    <Attribute>
      <Name>QueueArn</Name>
      <Value>arn:aws:sqs:us-east-2:123456789012:MyQueue</Value>
    </Attribute>
    <Attribute>
      <Name>MaximumMessageSize</Name>
      <Value>8192</Value>
    </Attribute>
    <Attribute>
      <Name>MessageRetentionPeriod</Name>
      <Value>345600</Value>
    </Attribute>
  </GetQueueAttributesResult>
  <ResponseMetadata>
    <RequestId>1ea71be5-b5a2-4f9d-b85a-945d8d08cd0b</RequestId>
  </ResponseMetadata>
</GetQueueAttributesResponse>
XML
        );

        $client = new MockHttpClient($response);
        $result = new GetQueueAttributesResult(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertArrayHasKey('MaximumMessageSize', $result->getAttributes());
        self::assertEquals('8192', $result->getAttributes()['MaximumMessageSize']);
    }
}
