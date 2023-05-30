<?php

namespace AsyncAws\MediaConvert\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\MediaConvert\Result\CancelJobResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CancelJobResponseTest extends TestCase
{
    public function testCancelJobResponse(): void
    {
        self::markTestSkipped('The response has no body to test');

        // see https://docs.aws.amazon.com/mediaconvert/latest/apireference/API_CancelJob.html
        $response = new SimpleMockedResponse('');

        $client = new MockHttpClient($response);
        $result = new CancelJobResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));
    }
}
