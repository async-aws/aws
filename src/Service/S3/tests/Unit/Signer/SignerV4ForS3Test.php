<?php

declare(strict_types=1);

namespace AsyncAws\S3\Tests\Unit\Signer;

use AsyncAws\Core\Configuration;
use AsyncAws\Core\Credentials\Credentials;
use AsyncAws\Core\Request;
use AsyncAws\Core\RequestContext;
use AsyncAws\Core\Stream\StringStream;
use AsyncAws\S3\Input\ListObjectsV2Request;
use AsyncAws\S3\Signer\SignerV4ForS3;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class SignerV4ForS3Test extends TestCase
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

    public function testSignWithChunkedStream()
    {
        $signer = new SignerV4ForS3('sqs', 'eu-west-1', [Configuration::OPTION_SEND_CHUNKED_BODY => true]);

        $request = new Request('POST', '/foo', ['arg' => 'bar'], [], StringStream::create(StringStream::create(str_repeat('a', 65536))));
        $request->setEndpoint('http://localhost:1234/foo?arg=bar');
        $context = new RequestContext(['currentDate' => new \DateTimeImmutable('2020-01-01T00:00:00Z')]);
        $credentials = new Credentials('key', 'secret', 'token');

        $signer->sign($request, $credentials, $context);

        $expectedHeaders = [
            'content-encoding' => 'aws-chunked',
            'content-md5' => 'LWGqVLWMLpRAP7CSw9vAJw==',
            'x-amz-content-sha256' => 'STREAMING-AWS4-HMAC-SHA256-PAYLOAD',
            'host' => 'localhost:1234',
            'x-amz-security-token' => 'token',
            'x-amz-date' => '20200101T000000Z',
            'x-amz-decoded-content-length' => '65536',
            'content-length' => '65712',
            'authorization' => 'AWS4-HMAC-SHA256 Credential=key/20200101/eu-west-1/sqs/aws4_request, SignedHeaders=content-encoding;content-md5;host;x-amz-content-sha256;x-amz-date;x-amz-decoded-content-length;x-amz-security-token, Signature=1f3faed8211c41b38ff9a2077b5ec6790219d369d07b6e21da5a70e24a2fb4d0',
        ];

        self::assertEqualsCanonicalizing($expectedHeaders, $request->getHeaders());
    }

    public function testSignWithChunkedStreamAndCustomEncoding()
    {
        $signer = new SignerV4ForS3('sqs', 'eu-west-1', [Configuration::OPTION_SEND_CHUNKED_BODY => true]);

        $request = new Request('POST', '/foo', ['arg' => 'bar'], ['content-encoding' => 'UTF-8'], StringStream::create(StringStream::create(str_repeat('a', 65536))));
        $request->setEndpoint('http://localhost:1234/foo?arg=bar');
        $context = new RequestContext(['currentDate' => new \DateTimeImmutable('2020-01-01T00:00:00Z')]);
        $credentials = new Credentials('key', 'secret', 'token');

        $signer->sign($request, $credentials, $context);

        $expectedHeaders = [
            'content-encoding' => 'aws-chunked, UTF-8',
            'content-md5' => 'LWGqVLWMLpRAP7CSw9vAJw==',
            'x-amz-content-sha256' => 'STREAMING-AWS4-HMAC-SHA256-PAYLOAD',
            'host' => 'localhost:1234',
            'x-amz-security-token' => 'token',
            'x-amz-date' => '20200101T000000Z',
            'x-amz-decoded-content-length' => '65536',
            'content-length' => '65712',
            'authorization' => 'AWS4-HMAC-SHA256 Credential=key/20200101/eu-west-1/sqs/aws4_request, SignedHeaders=content-encoding;content-md5;host;x-amz-content-sha256;x-amz-date;x-amz-decoded-content-length;x-amz-security-token, Signature=f754ab44e4a82320490de388b37506d833e4458de7f767a74d1b973b53c92d79',
        ];

        self::assertEqualsCanonicalizing($expectedHeaders, $request->getHeaders());
    }

    public function testSignWithCustomEncoding()
    {
        $signer = new SignerV4ForS3('sqs', 'eu-west-1', [Configuration::OPTION_SEND_CHUNKED_BODY => true]);

        $request = new Request('POST', '/foo', ['arg' => 'bar'], ['content-encoding' => 'UTF-8'], StringStream::create(StringStream::create(str_repeat('a', 1000))));
        $request->setEndpoint('http://localhost:1234/foo?arg=bar');
        $context = new RequestContext(['currentDate' => new \DateTimeImmutable('2020-01-01T00:00:00Z')]);
        $credentials = new Credentials('key', 'secret', 'token');

        $signer->sign($request, $credentials, $context);

        $expectedHeaders = [
            'content-encoding' => 'UTF-8',
            'content-md5' => 'yr5F3MmuW2a6hmAMymuLqA==',
            'x-amz-content-sha256' => '41edece42d63e8d9bf515a9ba6932e1c20cbc9f5a5d134645adb5db1b9737ea3',
            'host' => 'localhost:1234',
            'x-amz-security-token' => 'token',
            'x-amz-date' => '20200101T000000Z',
            'authorization' => 'AWS4-HMAC-SHA256 Credential=key/20200101/eu-west-1/sqs/aws4_request, SignedHeaders=content-encoding;content-md5;host;x-amz-content-sha256;x-amz-date;x-amz-security-token, Signature=8292648a7c5ebef873efa6ba32ed70cbe9ce76506f65c8d5ba43b0877e3982c9',
        ];

        self::assertEqualsCanonicalizing($expectedHeaders, $request->getHeaders());
    }
}
