<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Result\UploadPartOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class UploadPartOutputTest extends TestCase
{
    /**
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_UploadPart.html#API_UploadPart_Examples
     */
    public function testUploadPartOutput(): void
    {
        $response = new SimpleMockedResponse('', ['ETag' => '"b54357faf0632cce46e942fa68356b38"']);

        $client = new MockHttpClient($response);
        $result = new UploadPartOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('"b54357faf0632cce46e942fa68356b38"', $result->getETag());
    }
}
