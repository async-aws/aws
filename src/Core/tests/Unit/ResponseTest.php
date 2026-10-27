<?php

declare(strict_types=1);

namespace AsyncAws\Core\Tests\Unit;

use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\Core\Exception\RuntimeException;
use AsyncAws\Core\HttpClient\AwsRetryStrategy;
use AsyncAws\Core\Response;
use AsyncAws\Core\Stream\ResponseBodyNonBufferedStream;
use AsyncAws\Core\Stream\ResponseBodyStream;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpClient\RetryableHttpClient;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ResponseTest extends TestCase
{
    public function testDebugLoggingReadsBodiesUpToTheMaxSize(): void
    {
        $client = new MockHttpClient(new MockResponse('content', ['response_headers' => ['Content-Length' => '7']]));
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())
            ->method('debug')
            ->with('AsyncAws HTTP response received with status code {status_code}', self::callback(static function (array $context): bool {
                return 'content' === $context['body'];
            }))
        ;
        $response = new Response($client->request('GET', 'http://localhost', ['buffer' => $this->bufferUpTo(1)]), $client, $logger, null, null, null, true, [], 1);

        $response->resolve();

        self::assertTrue($response->info()['body_downloaded']);
    }

    public function testDebugLoggingDoesNotReadBodiesAboveTheMaxSize(): void
    {
        $body = str_repeat('a', 65537);
        $client = new MockHttpClient(new MockResponse($body, ['response_headers' => ['Content-Length' => '65537']]));
        $response = new Response($client->request('GET', 'http://localhost', ['buffer' => $this->bufferUpTo(1)]), $client, new NullLogger(), null, null, null, true, [], 1);

        $response->resolve();

        self::assertFalse($response->info()['body_downloaded']);
        self::assertSame($body, $response->toStream()->getContentAsString());
    }

    public function testGetContentReadsANonBufferedBody(): void
    {
        $body = str_repeat('a', 65537);
        $client = new MockHttpClient(new MockResponse($body, ['response_headers' => ['Content-Length' => '65537']]));
        $response = new Response($client->request('GET', 'http://localhost', ['buffer' => $this->bufferUpTo(1)]), $client, new NullLogger(), null, null, null, false, [], 1);

        self::assertSame($body, $response->getContent());
    }

    public function testGetContentReadsABufferedBodyThatWaitDownloaded(): void
    {
        $client = new MockHttpClient(new MockResponse('content', ['response_headers' => ['Content-Length' => '7']]));
        $response = new Response($client->request('GET', 'http://localhost', ['buffer' => $this->bufferUpTo(1)]), $client, new NullLogger(), null, null, null, false, [], 1);
        iterator_to_array(Response::wait([$response], null, true));

        self::assertSame('content', $response->getContent());
    }

    public function testGetContentThrowsWhenWaitStartedDownloadingANonBufferedBody(): void
    {
        $client = new MockHttpClient(new MockResponse(str_repeat('a', 65537), ['response_headers' => ['Content-Length' => '65537']]));
        $response = new Response($client->request('GET', 'http://localhost', ['buffer' => $this->bufferUpTo(1)]), $client, new NullLogger(), null, null, null, false, [], 1);
        iterator_to_array(Response::wait([$response], null, true));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Can not read the body because it is not buffered and it started being downloaded in Response::wait()');

        $response->getContent();
    }

    public function testToArrayReadsANonBufferedBody(): void
    {
        $body = json_encode(['a' => str_repeat('a', 65537)]);
        $client = new MockHttpClient(new MockResponse($body, ['response_headers' => ['Content-Length' => (string) \strlen($body)]]));
        $response = new Response($client->request('GET', 'http://localhost', ['buffer' => $this->bufferUpTo(1)]), $client, new NullLogger(), null, null, null, false, [], 1);

        self::assertSame(['a' => str_repeat('a', 65537)], $response->toArray());
    }

    public function testToArrayReadsABufferedBodyThatWaitDownloaded(): void
    {
        $client = new MockHttpClient(new MockResponse('{"a":"b"}', ['response_headers' => ['Content-Length' => '9']]));
        $response = new Response($client->request('GET', 'http://localhost', ['buffer' => $this->bufferUpTo(1)]), $client, new NullLogger(), null, null, null, false, [], 1);
        iterator_to_array(Response::wait([$response], null, true));

        self::assertSame(['a' => 'b'], $response->toArray());
    }

    public function testToArrayThrowsWhenWaitStartedDownloadingANonBufferedBody(): void
    {
        $client = new MockHttpClient(new MockResponse(str_repeat('a', 65537), ['response_headers' => ['Content-Length' => '65537']]));
        $response = new Response($client->request('GET', 'http://localhost', ['buffer' => $this->bufferUpTo(1)]), $client, new NullLogger(), null, null, null, false, [], 1);
        iterator_to_array(Response::wait([$response], null, true));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Can not read the body because it is not buffered and it started being downloaded in Response::wait()');

        $response->toArray();
    }

    public function testToStreamDefaultsToBufferedStream(): void
    {
        $body = str_repeat('a', 65537);
        $client = new MockHttpClient(new MockResponse($body, ['response_headers' => ['Content-Length' => '65537']]));
        $response = new Response($client->request('GET', 'http://localhost'), $client, new NullLogger());

        $stream = $response->toStream();

        self::assertInstanceOf(ResponseBodyStream::class, $stream);
        self::assertSame($body, $stream->getContentAsString());
    }

    public function testToStreamKeepsBodiesUpToTheMaxSizeBuffered(): void
    {
        $body = str_repeat('a', 65537);
        $client = new MockHttpClient(new MockResponse($body, ['response_headers' => ['Content-Length' => '65537']]));
        $response = new Response($client->request('GET', 'http://localhost', ['buffer' => $this->bufferUpTo(65537)]), $client, new NullLogger(), null, null, null, false, [], 65537);

        $stream = $response->toStream();

        self::assertInstanceOf(ResponseBodyStream::class, $stream);
        self::assertSame($body, $stream->getContentAsString());
        self::assertSame($body, $stream->getContentAsString());
    }

    public function testToStreamKeepsBodiesWithoutContentLengthBuffered(): void
    {
        $client = new MockHttpClient(new MockResponse('content'));
        $response = new Response($client->request('GET', 'http://localhost', ['buffer' => $this->bufferUpTo(1)]), $client, new NullLogger(), null, null, null, false, [], 1);

        $stream = $response->toStream();

        self::assertInstanceOf(ResponseBodyStream::class, $stream);
        self::assertSame('content', $stream->getContentAsString());
    }

    public function testToStreamReadsBodiesAboveTheMaxSizeFromTheHttpResponse(): void
    {
        $chunks = [str_repeat('a', 32768), str_repeat('b', 32769)];
        $client = new MockHttpClient(new MockResponse($chunks, ['response_headers' => ['Content-Length' => '65537']]));
        $httpResponse = $client->request('GET', 'http://localhost', ['buffer' => $this->bufferUpTo(65536)]);
        $response = new Response($httpResponse, $client, new NullLogger(), null, null, null, false, [], 65536);

        $stream = $response->toStream();

        self::assertInstanceOf(ResponseBodyNonBufferedStream::class, $stream);
        self::assertSame($chunks, iterator_to_array($stream->getChunks(), false));

        $this->expectException(TransportException::class);
        $this->expectExceptionMessage('buffering is disabled');

        $httpResponse->getContent(false);
    }

    public function testToStreamReadsBodiesAboveTheMaxSizeThroughRetries(): void
    {
        if (!class_exists(RetryableHttpClient::class)) {
            self::markTestSkipped('RetryableHttpClient requires symfony/http-client 5.2');
        }
        $body = str_repeat('a', 65537);
        $client = new RetryableHttpClient(new MockHttpClient([
            new MockResponse('<Error><Code>SlowDown</Code><Message>slow</Message></Error>', ['http_code' => 503]),
            new MockResponse($body, ['response_headers' => ['Content-Length' => '65537']]),
        ]), new AwsRetryStrategy(AwsRetryStrategy::DEFAULT_RETRY_STATUS_CODES, 0));
        $httpResponse = $client->request('GET', 'http://localhost', ['buffer' => $this->bufferUpTo(1)]);
        $response = new Response($httpResponse, $client, new NullLogger(), null, null, null, false, [], 1);

        $stream = $response->toStream();

        self::assertInstanceOf(ResponseBodyNonBufferedStream::class, $stream);
        self::assertSame($body, $stream->getContentAsString());

        $this->expectException(TransportException::class);
        $this->expectExceptionMessage('buffering is disabled');

        $httpResponse->getContent(false);
    }

    public function testErrorResponsesStayBufferedAndThrowTheMappedException(): void
    {
        $body = '<Error><Code>InvalidObjectState</Code><Message>Archived</Message></Error>';
        $client = new MockHttpClient(new MockResponse($body, ['http_code' => 403, 'response_headers' => ['Content-Length' => (string) \strlen($body)]]));
        $response = new Response($client->request('GET', 'http://localhost', ['buffer' => $this->bufferUpTo(1)]), $client, new NullLogger(), null, null, null, false, ['InvalidObjectState' => BodyReadingException::class], 1);

        $this->expectException(BodyReadingException::class);

        $response->toStream();
    }

    public function testErrorResponsesAboveTheMaxSizeThrowTheMappedException(): void
    {
        $item = str_repeat('a', 65537);
        $body = json_encode(['__type' => 'com.amazonaws.dynamodb.v20120810#ConditionalCheckFailedException', 'message' => 'The conditional request failed', 'Item' => $item]);
        $client = new MockHttpClient(new MockResponse($body, ['http_code' => 400, 'response_headers' => ['Content-Length' => (string) \strlen($body)]]));
        $response = new Response($client->request('GET', 'http://localhost', ['buffer' => $this->bufferUpTo(1)]), $client, new NullLogger(), null, null, null, false, ['ConditionalCheckFailedException' => ItemReadingException::class], 1);

        try {
            $response->toStream();
            self::fail('The mapped exception should have been thrown.');
        } catch (ItemReadingException $e) {
            self::assertSame($item, $e->getItem());
            self::assertSame($body, $e->getResponse()->getContent(false));
        }
    }

    private function bufferUpTo(int $maxSize): \Closure
    {
        return static function (array $headers) use ($maxSize): bool {
            return Response::shouldBufferBody($headers, $maxSize);
        };
    }
}

class BodyReadingException extends ClientException
{
    protected function populateResult(ResponseInterface $response): void
    {
        $response->getContent(false);
    }
}

class ItemReadingException extends ClientException
{
    private $item;

    public function getItem(): string
    {
        return $this->item;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $this->item = $response->toArray(false)['Item'];
    }
}
