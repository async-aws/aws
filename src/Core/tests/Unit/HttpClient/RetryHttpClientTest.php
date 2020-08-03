<?php

namespace AsyncAws\Core\Tests\Unit\HttpClient;

use AsyncAws\Core\Exception\Http\NetworkException;
use AsyncAws\Core\HttpClient\RetryHttpClient;
use AsyncAws\Core\Test\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class RetryHttpClientTest extends TestCase
{
    public function testRetryOn500Error(): void
    {
        $inner = $this->createMock(HttpClientInterface::class);
        $client = new RetryHttpClient($inner);

        $response1 = $this->createMock(ResponseInterface::class);
        $response1->expects(self::once())
            ->method('getStatusCode')
            ->willReturn(500);
        $response2 = $this->createMock(ResponseInterface::class);
        $response2->expects(self::once())
            ->method('getStatusCode')
            ->willReturn(200);
        $response2->expects(self::once())
            ->method('getContent')
            ->willReturn('ok');
        $inner->expects(self::exactly(2))
            ->method('request')
            ->with('GET', 'http://endpoint', [])
            ->willReturnOnConsecutiveCalls($response1, $response2);

        $response = $client->request('GET', 'http://endpoint');

        self::assertSame('ok', $response->getContent());
    }

    public function testRetryOnNetworkException(): void
    {
        $inner = $this->createMock(HttpClientInterface::class);
        $client = new RetryHttpClient($inner);

        $response1 = $this->createMock(ResponseInterface::class);
        $response1->expects(self::once())
            ->method('getStatusCode')
            ->willThrowException(new NetworkException());
        $response2 = $this->createMock(ResponseInterface::class);
        $response2->expects(self::once())
            ->method('getStatusCode')
            ->willReturn(200);
        $response2->expects(self::once())
            ->method('getContent')
            ->willReturn('ok');
        $inner->expects(self::exactly(2))
            ->method('request')
            ->with('GET', 'http://endpoint', [])
            ->willReturnOnConsecutiveCalls($response1, $response2);

        $response = $client->request('GET', 'http://endpoint');

        self::assertSame('ok', $response->getContent());
    }

    public function testNoRetryOn400Error(): void
    {
        $inner = $this->createMock(HttpClientInterface::class);
        $client = new RetryHttpClient($inner);

        $response1 = $this->createMock(ResponseInterface::class);
        $response1->expects(self::once())
            ->method('getStatusCode')
            ->willReturn(400);
        $response1->expects(self::once())
            ->method('getContent')
            ->willReturn('ok');
        $inner->expects(self::exactly(1))
            ->method('request')
            ->with('GET', 'http://endpoint', [])
            ->willReturnOnConsecutiveCalls($response1);

        $response = $client->request('GET', 'http://endpoint');

        self::assertSame('ok', $response->getContent());
    }
}
