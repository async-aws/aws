<?php

declare(strict_types=1);

namespace AsyncAws\SimpleS3\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\SimpleS3\SimpleS3Client;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class SimpleS3ClientTest extends TestCase
{
    public function testGetUrlHostStyle()
    {
        $client = new SimpleS3Client(['region' => 'eu-central-1'], new NullProvider(), new MockHttpClient());
        $url = $client->getUrl('bucket', 'images/file.jpg');
        self::assertSame('https://bucket.s3.eu-central-1.amazonaws.com/images/file.jpg', $url);
    }

    public function testGetUrlPathStyle()
    {
        $client = new SimpleS3Client(['region' => 'eu-central-1', 'pathStyleEndpoint' => true], new NullProvider(), new MockHttpClient());
        $url = $client->getUrl('bucket', 'images/file.jpg');
        self::assertSame('https://s3.eu-central-1.amazonaws.com/bucket/images/file.jpg', $url);
    }
}
