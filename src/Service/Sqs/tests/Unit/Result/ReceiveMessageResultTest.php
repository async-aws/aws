<?php

declare(strict_types=1);

namespace AsyncAws\Sqs\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Sqs\Result\ReceiveMessageResult;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class ReceiveMessageResultTest extends TestCase
{
    public function testReceiveMessageResult()
    {
        $response = new SimpleMockedResponse(<<<XML
<ReceiveMessageResponse>
  <ReceiveMessageResult>
    <Message>
      <MessageId>5fea7756-0ea4-451a-a703-a558b933e274</MessageId>
      <ReceiptHandle>
        MbZj6wDWli+JvwwJaBV+3dcjk2YW2vA3+STFFljTM8tJJg6HRG6PYSasuWXPJB+Cw
        Lj1FjgXUv1uSj1gUPAWV66FU/WeR4mq2OKpEGYWbnLmpRCJVAyeMjeU5ZBdtcQ+QE
        auMZc8ZRv37sIW2iJKq3M9MFx1YvV11A2x/KSbkJ0=
      </ReceiptHandle>
      <MD5OfBody>fafb00f5732ab283681e124bf8747ed1</MD5OfBody>
      <Body>This is a test message</Body>
      <Attribute>
        <Name>SenderId</Name>
        <Value>195004372649</Value>
      </Attribute>
      <Attribute>
        <Name>SentTimestamp</Name>
        <Value>1238099229000</Value>
      </Attribute>
      <Attribute>
        <Name>ApproximateReceiveCount</Name>
        <Value>5</Value>
      </Attribute>
      <Attribute>
        <Name>ApproximateFirstReceiveTimestamp</Name>
        <Value>1250700979248</Value>
      </Attribute>
    </Message>
  </ReceiveMessageResult>
  <ResponseMetadata>
    <RequestId>b6633655-283d-45b4-aee4-4e84e0ae6afa</RequestId>
  </ResponseMetadata>
</ReceiveMessageResponse>
XML
        );

        $client = new MockHttpClient($response);
        $result = new ReceiveMessageResult(new Response($client->request('POST', 'http://localhost'), $client));

        self::assertCount(1, $result->getMessages());
        $message = $result->getMessages()[0];
        self::assertEquals('5fea7756-0ea4-451a-a703-a558b933e274', $message->getMessageId());
        self::assertEquals('fafb00f5732ab283681e124bf8747ed1', $message->getMD5OfBody());
        self::assertArrayHasKey('SenderId', $message->getAttributes());
        self::assertEquals('195004372649', $message->getAttributes()['SenderId']);
        self::assertArrayHasKey('ApproximateFirstReceiveTimestamp', $message->getAttributes());
        self::assertEquals('1250700979248', $message->getAttributes()['ApproximateFirstReceiveTimestamp']);
    }
}
