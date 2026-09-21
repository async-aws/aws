<?php

declare(strict_types=1);

namespace AsyncAws\Test\Unit;

use AsyncAws\Core\Credentials\Credentials;
use AsyncAws\Core\RequestContext;
use AsyncAws\S3\Input\ListObjectsV2Request;
use AsyncAws\S3\Signer\SignerV4ForS3;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Test the in-repository Core signer; S3's component suite also runs with older Core releases.
 */
class S3SignatureTest extends TestCase
{
    #[DataProvider('provideListObjectsEndpoints')]
    public function testSignListObjectsWithContentType(string $endpoint, string $signature): void
    {
        $request = (new ListObjectsV2Request(['Bucket' => 'bucket', 'Prefix' => 'sitemap/', 'MaxKeys' => 1]))->request();
        $request->setEndpoint($endpoint);

        (new SignerV4ForS3('s3', 'eu-west-1'))->sign($request, new Credentials('key', 'secret', 'token'), new RequestContext([
            'operation' => 'ListObjectsV2',
            'currentDate' => new \DateTimeImmutable('2020-01-01T00:00:00Z'),
        ]));

        self::assertSame('application/xml', $request->getHeader('Content-Type'));
        self::assertSame('AWS4-HMAC-SHA256 Credential=key/20200101/eu-west-1/s3/aws4_request, SignedHeaders=content-type;host;x-amz-content-sha256;x-amz-date;x-amz-security-token, Signature=' . $signature, $request->getHeader('Authorization'));
    }

    public static function provideListObjectsEndpoints(): iterable
    {
        // Fixed signatures cross-checked with aws/aws-sdk-php 3.395.6.
        yield 'path style' => ['https://s3.example.com/bucket?list-type=2&max-keys=1&prefix=sitemap%2F', '83bf718c7339a0e838e67a66684c88e679cc1a3570ea37e8a1338a371fa83fd1'];
        yield 'virtual host' => ['https://bucket.s3.example.com/?list-type=2&max-keys=1&prefix=sitemap%2F', '89a9b169e48086d514b2abf6f45e97e834c438a9840b14f141d43f3772211f86'];
    }
}
