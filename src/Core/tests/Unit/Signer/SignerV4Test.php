<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit\Signer;

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

    /**
     * @dataProvider provideRequests
     */
    public function testSignsRequests($rawRequest, $rawExpected)
    {
        $request = $this->parseRequest($rawRequest);

        $signer = new SignerV4('host', 'us-east-1');
        $context = new RequestContext(['currentDate' => new \DateTimeImmutable('20110909T233600Z')]);
        $credentials = new Credentials('AKIDEXAMPLE', 'wJalrXUtnFEMI/K7MDENG+bPxRfiCYEXAMPLEKEY');

        $signer->sign($request, $credentials, $context);

        $expected = $this->parseRequest($rawExpected);

        self::assertEquals($expected, $request);
    }

    public function provideRequests()
    {
        return [
            // POST headers should be signed.
            [
                "POST / HTTP/1.1\r\nHost: host.foo.com\r\nx-AMZ-date: 20110909T233600Z\r\nZOO:zoobar\r\n\r\n",
                "POST / HTTP/1.1\r\nHost: host.foo.com\r\nZOO: zoobar\r\nX-Amz-Date: 20110909T233600Z\r\nAuthorization: AWS4-HMAC-SHA256 Credential=AKIDEXAMPLE/20110909/us-east-1/host/aws4_request, SignedHeaders=host;x-amz-date;zoo, Signature=b28a4d452e58edf8ff150a9518b6f4135c9960e4724dc3daab4d7ccc26e90b9b\r\n\r\n",
            ],
            // Changing the method should change the signature.
            [
                "GET / HTTP/1.1\r\nHost: host.foo.com\r\nx-AMZ-date: 20110909T233600Z\r\nZOO:zoobar\r\n\r\n",
                "GET / HTTP/1.1\r\nHost: host.foo.com\r\nZOO: zoobar\r\nX-Amz-Date: 20110909T233600Z\r\nAuthorization: AWS4-HMAC-SHA256 Credential=AKIDEXAMPLE/20110909/us-east-1/host/aws4_request, SignedHeaders=host;x-amz-date;zoo, Signature=287deb2c1249c9c415cb4b3ef74404629fcab56a8e9ec568bff88cf093196e8e\r\n\r\n",
            ],
            // Duplicate header values must be sorted.
            [
                "POST / HTTP/1.1\r\nHost: host.foo.com\r\nx-AMZ-date: 20110909T233600Z\r\np: z\r\np: a\r\np: p\r\np: a\r\n\r\n",
                "POST / HTTP/1.1\r\nHost: host.foo.com\r\np: z, a, p, a\r\nX-Amz-Date: 20110909T233600Z\r\nAuthorization: AWS4-HMAC-SHA256 Credential=AKIDEXAMPLE/20110909/us-east-1/host/aws4_request, SignedHeaders=host;p;x-amz-date, Signature=faca06aa6ae71c0a24116c9a61b01346e6d9d621001bac49d38a6fdb285649ec\r\n\r\n",
            ],
            // Request with space.
            [
                "GET /%20/foo HTTP/1.1\r\nHost: host.foo.com\r\n\r\n",
                "GET /%20/foo HTTP/1.1\r\nHost: host.foo.com\r\nX-Amz-Date: 20110909T233600Z\r\nAuthorization: AWS4-HMAC-SHA256 Credential=AKIDEXAMPLE/20110909/us-east-1/host/aws4_request, SignedHeaders=host;x-amz-date, Signature=948b2292a8bcb4510013741d64c5667f75d46dd6c4896ead5d669eb8264ebe1f\r\n\r\n",
            ],
            // Query order key
            [
                "GET /?a=foo&b=foo HTTP/1.1\r\nHost: host.foo.com\r\n\r\n",
                "GET /?a=foo&b=foo HTTP/1.1\r\nHost: host.foo.com\r\nX-Amz-Date: 20110909T233600Z\r\nAuthorization: AWS4-HMAC-SHA256 Credential=AKIDEXAMPLE/20110909/us-east-1/host/aws4_request, SignedHeaders=host;x-amz-date, Signature=1cfa3132ddd1b16d824aacef668c131d9096fe52e6d5718e20e43a7e47f616c6\r\n\r\n",
            ],
            // POST with body
            [
                "POST / HTTP/1.1\r\nHost: host.foo.com\r\nContent-Length: 4\r\n\r\nTest",
                "POST / HTTP/1.1\r\nHost: host.foo.com\r\nContent-Length: 4\r\nX-Amz-Date: 20110909T233600Z\r\nAuthorization: AWS4-HMAC-SHA256 Credential=AKIDEXAMPLE/20110909/us-east-1/host/aws4_request, SignedHeaders=host;x-amz-date, Signature=277a7dcbb942ea6290173548feee1df1a7550354dc83e22daf5ffea86a44e0db\r\n\r\nTest",
            ],
            // HTTPS POST headers should be signed.
            [
                "POST / HTTP/1.1\r\nHost: host.foo.com:443\r\nx-AMZ-date: 20110909T233600Z\r\nZOO:zoobar\r\n\r\n",
                "POST / HTTP/1.1\r\nHost: host.foo.com:443\r\nZOO: zoobar\r\nX-Amz-Date: 20110909T233600Z\r\nAuthorization: AWS4-HMAC-SHA256 Credential=AKIDEXAMPLE/20110909/us-east-1/host/aws4_request, SignedHeaders=host;x-amz-date;zoo, Signature=d02686375a2514d5bcdc0c4609fdeb80a149f559f7bde45c790c23f3bed62c15\r\n\r\n",
            ],
            // HTTPS Changing the method should change the signature.
            [
                "GET / HTTP/1.1\r\nHost: host.foo.com:443\r\nx-AMZ-date: 20110909T233600Z\r\nZOO:zoobar\r\n\r\n",
                "GET / HTTP/1.1\r\nHost: host.foo.com:443\r\nZOO: zoobar\r\nX-Amz-Date: 20110909T233600Z\r\nAuthorization: AWS4-HMAC-SHA256 Credential=AKIDEXAMPLE/20110909/us-east-1/host/aws4_request, SignedHeaders=host;x-amz-date;zoo, Signature=69c57723eee136a804b6d4b1fd1b4d45ba059e1f758900a6b1301111e1e8c77e\r\n\r\n",
            ],
            // HTTPS Duplicate header values must be sorted.
            [
                "POST / HTTP/1.1\r\nHost: host.foo.com:443\r\nx-AMZ-date: 20110909T233600Z\r\np: z\r\np: a\r\np: p\r\np: a\r\n\r\n",
                "POST / HTTP/1.1\r\nHost: host.foo.com:443\r\np: z, a, p, a\r\nX-Amz-Date: 20110909T233600Z\r\nAuthorization: AWS4-HMAC-SHA256 Credential=AKIDEXAMPLE/20110909/us-east-1/host/aws4_request, SignedHeaders=host;p;x-amz-date, Signature=cec423fa9e930519918d3c05982c14ae60b7c5aedd296f2a1322b5831bbaf4ea\r\n\r\n",
            ],
            // HTTPS Request with space.
            [
                "GET /%20/foo HTTP/1.1\r\nHost: host.foo.com:443\r\n\r\n",
                "GET /%20/foo HTTP/1.1\r\nHost: host.foo.com:443\r\nX-Amz-Date: 20110909T233600Z\r\nAuthorization: AWS4-HMAC-SHA256 Credential=AKIDEXAMPLE/20110909/us-east-1/host/aws4_request, SignedHeaders=host;x-amz-date, Signature=5a55c5e2f146b167c3026dd5586bb1d85d530b1dd4a9dfe7cf7966eee3e92d2c\r\n\r\n",
            ],
            // HTTPS Query order key
            [
                "GET /?a=foo&b=foo HTTP/1.1\r\nHost: host.foo.com:443\r\n\r\n",
                "GET /?a=foo&b=foo HTTP/1.1\r\nHost: host.foo.com:443\r\nX-Amz-Date: 20110909T233600Z\r\nAuthorization: AWS4-HMAC-SHA256 Credential=AKIDEXAMPLE/20110909/us-east-1/host/aws4_request, SignedHeaders=host;x-amz-date, Signature=1c3274381ae12d8817336268d7da17672bd57e7348e39b7b9c567280f73742af\r\n\r\n",
            ],
            // HTTPS POST with body
            [
                "POST / HTTP/1.1\r\nHost: host.foo.com:443\r\nContent-Length: 4\r\n\r\nTest",
                "POST / HTTP/1.1\r\nHost: host.foo.com:443\r\nContent-Length: 4\r\nX-Amz-Date: 20110909T233600Z\r\nAuthorization: AWS4-HMAC-SHA256 Credential=AKIDEXAMPLE/20110909/us-east-1/host/aws4_request, SignedHeaders=host;x-amz-date, Signature=e971be49c79358595ef6214f683ac9c0489d397a5d5d13b361291e751deeca03\r\n\r\nTest",
                "POST\n/\n\nhost:host.foo.com:443\n\nhost\n532eaabd9574880dbf76b9b8cc00832c20a6ec113d682299550d7a6e0f345e25",
            ],
        ];
    }

    private function parseRequest(string $req): Request
    {
        $lines = explode("\r\n", $req);
        [$method, $path] = explode(' ', \array_shift($lines));
        $headers = [];
        while ('' !== $line = \array_shift($lines)) {
            [$name, $value] = \explode(':', $line, 2);
            if (isset($headers[$name])) {
                $headers[$name] = $headers[$name] . ',' . $value;
            } else {
                $headers[$name] = trim($value);
            }
        }

        $req = new Request($method, '/', [], $headers, StringStream::create(\implode("\n", $lines)));
        $req->setEndpoint('https://' . $headers['Host'] . $path);

        return $req;
    }
}
