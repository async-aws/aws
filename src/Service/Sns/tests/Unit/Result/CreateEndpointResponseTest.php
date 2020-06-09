<?php

namespace AsyncAws\Sns\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sns\Result\CreateEndpointResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CreateEndpointResponseTest extends TestCase
{
    public function testCreateEndpointResponse(): void
    {
        // see https://docs.aws.amazon.com/sns/latest/api/API_CreatePlatformEndpoint.html
        $response = new SimpleMockedResponse('<CreatePlatformEndpointResponse xmlns="https://sns.amazonaws.com/doc/2010-03-31/">
    <CreatePlatformEndpointResult>
        <EndpointArn>arn:aws:sns:us-west-2:123456789012:endpoint/GCM/gcmpushapp/5e3e9847-3183-3f18-a7e8-671c3a57d4b3</EndpointArn>
    </CreatePlatformEndpointResult>
    <ResponseMetadata>
        <RequestId>6613341d-3e15-53f7-bf3c-7e56994ba278</RequestId>
    </ResponseMetadata>
</CreatePlatformEndpointResponse>');

        $client = new MockHttpClient($response);
        $result = new CreateEndpointResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('arn:aws:sns:us-west-2:123456789012:endpoint/GCM/gcmpushapp/5e3e9847-3183-3f18-a7e8-671c3a57d4b3', $result->getEndpointArn());
    }
}
