<?php

namespace AsyncAws\Core\Tests\HttpClient;

use AsyncAws\Core\HttpClient\BufferedResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\Exception\RedirectionException;
use Symfony\Component\HttpClient\Exception\ServerException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;

class BufferedResponseTest extends TestCase
{
    #[DataProvider('provideErrorStatusCodes')]
    public function testGetHeadersThrowsForErrorStatusCodesOnceTheBodyIsRead(int $statusCode, string $exceptionClass): void
    {
        $client = new MockHttpClient(new MockResponse('{"message":"Denied"}', ['http_code' => $statusCode, 'response_headers' => ['Content-Type' => 'application/x-amz-json-1.0']]));
        $response = new BufferedResponse($client->request('GET', 'http://localhost', ['buffer' => false]));

        self::assertSame('{"message":"Denied"}', $response->getContent(false));

        $this->expectException($exceptionClass);

        $response->getHeaders();
    }

    public static function provideErrorStatusCodes(): iterable
    {
        yield [300, RedirectionException::class];
        yield [400, ClientException::class];
        yield [500, ServerException::class];
    }

    public function testGetContentThrowsForErrorStatusCodesOnceTheBodyIsRead(): void
    {
        $client = new MockHttpClient(new MockResponse('{"message":"Denied"}', ['http_code' => 400, 'response_headers' => ['Content-Type' => 'application/x-amz-json-1.0']]));
        $response = new BufferedResponse($client->request('GET', 'http://localhost', ['buffer' => false]));

        self::assertSame('{"message":"Denied"}', $response->getContent(false));

        $this->expectException(ClientException::class);

        $response->getContent();
    }

    public function testToArrayThrowsForErrorStatusCodesOnceTheBodyIsRead(): void
    {
        $client = new MockHttpClient(new MockResponse('{"message":"Denied"}', ['http_code' => 400, 'response_headers' => ['Content-Type' => 'application/x-amz-json-1.0']]));
        $response = new BufferedResponse($client->request('GET', 'http://localhost', ['buffer' => false]));

        self::assertSame(['message' => 'Denied'], $response->toArray(false));

        $this->expectException(ClientException::class);

        $response->toArray();
    }

    public function testToArrayThrowsForBodiesThatAreNotJson(): void
    {
        $client = new MockHttpClient(new MockResponse('<Error><Code>Denied</Code></Error>', ['http_code' => 400]));
        $response = new BufferedResponse($client->request('GET', 'http://localhost', ['buffer' => false]));

        $this->expectException(DecodingExceptionInterface::class);

        $response->toArray(false);
    }
}
