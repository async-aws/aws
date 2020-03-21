<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit;

use AsyncAws\Core\Credentials\Credentials;
use AsyncAws\Core\Request;
use AsyncAws\Core\RequestContext;
use AsyncAws\Core\Signer\SignerV4;
use AsyncAws\Core\Stream\StringStream;
use PHPUnit\Framework\TestCase;

class SignerV4Test extends TestCase
{
    public function testSign()
    {
        $signer = new SignerV4('sqs', 'eu-west-1');

        $request = new Request('POST', '/foo', ['arg' => 'bar'], ['header' => 'baz'], StringStream::create('body'));
        $request->setEndpoint('http://localhost:1234/foo?arg=bar');
        $context = new RequestContext(['currentDate' => new \DateTimeImmutable('2020-01-01T00:00:00Z')]);
        $credentials = new Credentials('key', 'secret', 'token');

        $signer->sign($request, $credentials, $context);

        $expectedHeaders = [
            'header' => 'baz',
            'host' => 'localhost:1234',
            'x-amz-security-token' => 'token',
            'x-amz-date' => '20200101T000000Z',
            'content-length' => 4,
            'authorization' => 'AWS4-HMAC-SHA256 Credential=key/20200101/eu-west-1/sqs/aws4_request, SignedHeaders=header;host;x-amz-date;x-amz-security-token, Signature=87e3d70ecfaf7655c24284198c90c61da166aea5bbe2eb3fe470634369acb108',
        ];

        self::assertEqualsCanonicalizing($expectedHeaders, $request->getHeaders());
    }

    public function testPresign()
    {
        $signer = new SignerV4('sqs', 'eu-west-1');

        $request = new Request('POST', '/foo', ['arg' => 'bar'], ['header' => 'baz'], StringStream::create('body'));
        $request->setEndpoint('http://localhost:1234/foo?arg=bar');
        $context = new RequestContext(['currentDate' => new \DateTimeImmutable('2020-01-01T00:00:00Z')]);
        $credentials = new Credentials('key', 'secret', 'token');

        $signer->presign($request, $credentials, $context);

        $expectedQuery = [
            'arg' => 'bar',
            'X-Amz-Algorithm' => 'AWS4-HMAC-SHA256',
            'X-Amz-Security-Token' => 'token',
            'X-Amz-Date' => '20200101T000000Z',
            'X-Amz-Expires' => 3600,
            'X-Amz-Credential' => 'key/20200101/eu-west-1/sqs/aws4_request',
            'X-Amz-SignedHeaders' => 'header;host',
            'X-Amz-Signature' => '27d875a8ba472ef2c315e2120e7718c4187b4b07f6b787264858227586c0445a',
        ];

        self::assertEqualsCanonicalizing($expectedQuery, $request->getQuery());
    }
}
