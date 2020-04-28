<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\S3\Result\HeadObjectOutput;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class HeadObjectOutputTest extends TestCase
{
    public function testHeadObjectOutput(): void
    {
        $headers = [
            'x-amz-id-2' => [0 => 'Hb0J19YAz/KDAIFnXuHRCISFYgL2rpPk4F0JmtKEd8eMwbD2N8ayhfW3dUalBuQGK4kEo6d3MBY='],
            'x-amz-request-id' => [0 => 'C9DC31A2888EF715'],
            'date' => [0 => 'Sun, 23 Feb 2020 08:56:00 GMT'],
            'last-modified' => [0 => 'Sun, 23 Feb 2020 08:56:00 GMT'],
            'etag' => [0 => '"98bf7d8c15784f0a3d63204441e1e2aa"'],
            'accept-ranges' => [0 => 'bytes'],
            'content-type' => [0 => 'text/plain+special'],
            'content-length' => [0 => '0'],
            'server' => [0 => 'AmazonS3'],
        ];

        $response = new SimpleMockedResponse('', $headers);

        $client = new MockHttpClient($response);
        $result = new HeadObjectOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertNull($result->getDeleteMarker());
        self::assertEquals('bytes', $result->getAcceptRanges());
        self::assertEquals(1582448160, $result->getLastModified()->getTimestamp());
        self::assertEquals(0, $result->getContentLength());
        self::assertEquals('text/plain+special', $result->getContentType());
        self::assertEquals('"98bf7d8c15784f0a3d63204441e1e2aa"', $result->getETag());
    }
}
