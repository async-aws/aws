<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit;

use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\Core\Response;
use AsyncAws\Core\Stream\ResponseBodyNonBufferedStream;
use AsyncAws\Core\Stream\ResponseBodyStream;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ResponseTest extends TestCase
{
    public function testToStreamDefaultsToBufferedStream(): void
    {
        $httpResponse = new SimpleMockedResponse('content');
        $client = new MockHttpClient($httpResponse);
        $response = new Response($client->request('GET', 'http://localhost'), $client, new NullLogger());

        self::assertInstanceOf(ResponseBodyStream::class, $response->toStream());
    }

    public function testToStreamCanReturnNonBufferedStream(): void
    {
        $httpResponse = new SimpleMockedResponse('content');
        $client = new MockHttpClient($httpResponse);
        $response = new Response($client->request('GET', 'http://localhost'), $client, new NullLogger(), null, null, null, false, [], false);

        $stream = $response->toStream();

        self::assertInstanceOf(ResponseBodyNonBufferedStream::class, $stream);
        self::assertSame('content', $stream->getContentAsString());
    }

    public function testToStreamResolvesHttpErrorsBeforeReturningNonBufferedStream(): void
    {
        $httpResponse = new SimpleMockedResponse('Bad request', [], 400);
        $client = new MockHttpClient($httpResponse);
        $response = new Response($client->request('GET', 'http://localhost'), $client, new NullLogger(), null, null, null, false, [], false);

        $this->expectException(ClientException::class);

        $response->toStream();
    }

    public function testDebugLoggingDoesNotReadBodyWhenBufferingIsDisabled(): void
    {
        $httpResponse = new NonContentResponse();
        $client = $this->createMock(HttpClientInterface::class);
        $response = new Response($httpResponse, $client, new NullLogger(), null, null, null, true, [], false);

        $response->resolve();

        self::assertTrue($response->info()['resolved']);
        self::assertFalse($response->info()['body_downloaded']);
    }
}

class NonContentResponse implements ResponseInterface
{
    public function getStatusCode(): int
    {
        return 200;
    }

    public function getHeaders(bool $throw = true): array
    {
        return [];
    }

    public function getContent(bool $throw = true): string
    {
        throw new \RuntimeException('getContent should not be called.');
    }

    public function toArray(bool $throw = true): array
    {
        return [];
    }

    public function cancel(): void
    {
    }

    public function getInfo(?string $type = null): mixed
    {
        $info = [
            'http_code' => 200,
            'url' => 'http://localhost',
        ];

        return null === $type ? $info : ($info[$type] ?? null);
    }
}
