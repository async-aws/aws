<?php

declare(strict_types=1);

namespace AsyncAws\SimpleS3\Tests\Unit;

use AsyncAws\Core\Credentials\Credentials;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\S3\S3Configuration;
use AsyncAws\SimpleS3\SimpleS3Client;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class SimpleS3ClientTest extends TestCase
{
    public function testGetUrl()
    {
        $client = new SimpleS3Client(['region' => 'eu-central-1'], new Credentials('id', 'secret'), new MockHttpClient());
        $url = $client->getUrl('bucket', 'images/file.jpg');
        if (\class_exists(S3Configuration::class)) {
            self::assertSame('https://bucket.s3.eu-central-1.amazonaws.com/images/file.jpg', $url);
        } else {
            self::assertSame('https://s3.eu-central-1.amazonaws.com/bucket/images/file.jpg', $url);
        }
    }

    public function testGetUrlWithNoCredentials()
    {
        $client = new SimpleS3Client(['region' => 'eu-central-1'], new NullProvider(), new MockHttpClient());
        $url = $client->getUrl('bucket', 'images/file.jpg');
        if (\class_exists(S3Configuration::class)) {
            self::assertSame('https://bucket.s3.eu-central-1.amazonaws.com/images/file.jpg', $url);
        } else {
            self::assertSame('https://s3.eu-central-1.amazonaws.com/bucket/images/file.jpg', $url);
        }
    }
}
