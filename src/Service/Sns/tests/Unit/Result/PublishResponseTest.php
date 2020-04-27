<?php

namespace AsyncAws\Sns\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Sns\Result\PublishResponse;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class PublishResponseTest extends TestCase
{
    /**
     * @see https://docs.aws.amazon.com/sns/latest/api/API_Publish.html#API_Publish_Examples
     */
    public function testPublishResponse(): void
    {
        $response = new SimpleMockedResponse('<PublishResponse xmlns="https://sns.amazonaws.com/doc/2010-03-31/">
    <PublishResult>
        <MessageId>567910cd-659e-55d4-8ccb-5aaf14679dc0</MessageId>
    </PublishResult>
    <ResponseMetadata>
        <RequestId>d74b8436-ae13-5ab4-a9ff-ce54dfea72a0</RequestId>
    </ResponseMetadata>
</PublishResponse>');

        $client = new MockHttpClient($response);
        $result = new PublishResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertEquals('567910cd-659e-55d4-8ccb-5aaf14679dc0', $result->getMessageId());
    }
}
