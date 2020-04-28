<?php

declare(strict_types=1);

namespace AsyncAws\Core;

use AsyncAws\Core\Exception\Exception;
use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\Core\Exception\Http\HttpException;
use AsyncAws\Core\Exception\Http\NetworkException;
use AsyncAws\Core\Exception\Http\RedirectionException;
use AsyncAws\Core\Exception\Http\ServerException;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Exception\LogicException;
use AsyncAws\Core\Exception\RuntimeException;
use AsyncAws\Core\Stream\ResponseBodyResourceStream;
use AsyncAws\Core\Stream\ResponseBodyStream;
use AsyncAws\Core\Stream\ResultStream;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The response provides a facade to manipulate HttpResponses.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class Response
{
    private $httpResponse;

    private $httpClient;

    /**
     * A Result can be resolved many times. This variable contains the last resolve result.
     * Null means that the result has never been resolved. Array contains material to create an exception.
     *
     * @var bool|HttpException|NetworkException|array|null
     */
    private $resolveResult;

    /**
     * A flag that indicated that the body have been downloaded.
     *
     * @var bool
     */
    private $bodyDownloaded = false;

    /**
     * A flag that indicated that the body started being downloaded.
     *
     * @var bool
     */
    private $streamStarted = false;

    /**
     * A flag that indicated that an exception has been thrown to the user.
     */
    private $didThrow = false;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(ResponseInterface $response, HttpClientInterface $httpClient, LoggerInterface $logger)
    {
        $this->httpResponse = $response;
        $this->httpClient = $httpClient;
        $this->logger = $logger;
    }

    public function __destruct()
    {
        if (null === $this->resolveResult || !$this->didThrow) {
            $this->resolve();
        }
    }

    /**
     * Make sure the actual request is executed.
     *
     * @param float|null $timeout Duration in seconds before aborting. When null wait
     *                            until the end of execution. Using 0 means non-blocking
     *
     * @return bool whether the request is executed or not
     *
     * @throws NetworkException
     * @throws HttpException
     */
    public function resolve(?float $timeout = null): bool
    {
        if (null !== $this->resolveResult) {
            return $this->getResolveStatus();
        }

        try {
            foreach ($this->httpClient->stream($this->httpResponse, $timeout) as $chunk) {
                if ($chunk->isTimeout()) {
                    return false;
                }
                if ($chunk->isFirst()) {
                    break;
                }
            }

            $this->defineResolveStatus();
        } catch (TransportExceptionInterface $e) {
            $this->resolveResult = new NetworkException('Could not contact remote server.', 0, $e);
        }

        return $this->getResolveStatus();
    }

    /**
     * Make sure all provided requests are executed.
     *
     * @param self[]     $responses
     * @param float|null $timeout      Duration in seconds before aborting. When null wait
     *                                 until the end of execution. Using 0 means non-blocking
     * @param bool       $downloadBody Wait until receiving the entire response body or only the first bytes
     *
     * @return iterable<self>
     *
     * @throws NetworkException
     * @throws HttpException
     */
    final public static function wait(iterable $responses, float $timeout = null, bool $downloadBody = false): iterable
    {
        /** @var self[] $responseMap */
        $responseMap = [];
        $indexMap = [];
        $httpResponses = [];
        $httpClient = null;
        foreach ($responses as $index => $response) {
            if (null !== $response->resolveResult && (true !== $response->resolveResult || !$downloadBody || $response->bodyDownloaded)) {
                yield $index => $response;

                continue;
            }

            if (null === $httpClient) {
                $httpClient = $response->httpClient;
            } elseif ($httpClient !== $response->httpClient) {
                throw new LogicException('Unable to wait for the given results, they all have to be created with the same HttpClient');
            }
            $httpResponses[] = $response->httpResponse;
            $indexMap[$hash = \spl_object_id($response->httpResponse)] = $index;
            $responseMap[$hash] = $response;
        }

        // no response provided (or all responses already resolved)
        if (empty($httpResponses)) {
            return;
        }

        if (null === $httpClient) {
            throw new InvalidArgument('At least one response should have contain an Http Client');
        }

        foreach ($httpClient->stream($httpResponses, $timeout) as $httpResponse => $chunk) {
            $hash = \spl_object_id($httpResponse);
            $response = $responseMap[$hash] ?? null;
            // Check if null, just in case symfony yield an unexpected response.
            if (null === $response) {
                continue;
            }

            // index could be null if already yield
            $index = $indexMap[$hash] ?? null;

            try {
                if ($chunk->isTimeout()) {
                    // Receiving a timeout mean all responses are inactive.
                    break;
                }
            } catch (TransportException $e) {
                // Exception is stored as an array, because storing an instance of \Exception will create a circular
                // reference and prevent `__destruct` being called.
                $response->resolveResult = [NetworkException::class, ['Could not contact remote server.', 0, $e]];

                if (null !== $index) {
                    unset($indexMap[$hash]);
                    yield $index => $response;
                    if (empty($indexMap)) {
                        // early exit if all statusCode are known. We don't have to wait for all responses
                        return;
                    }
                }
            }

            if (!$response->streamStarted && '' !== $chunk->getContent()) {
                $response->streamStarted = true;
            }

            if ($chunk->isLast()) {
                $response->bodyDownloaded = true;
                if (null !== $index && $downloadBody) {
                    unset($indexMap[$hash]);
                    yield $index => $response;
                }
            }
            if ($chunk->isFirst()) {
                $response->defineResolveStatus();
                if (null !== $index && !$downloadBody) {
                    unset($indexMap[$hash]);
                    yield $index => $response;
                }
            }

            if (empty($indexMap)) {
                // early exit if all statusCode are known. We don't have to wait for all responses
                return;
            }
        }
    }

    /**
     * Returns info on the current request.
     *
     * @return array{
     *                resolved: bool,
     *                body_downloaded: bool,
     *                response: \Symfony\Contracts\HttpClient\ResponseInterface,
     *                status: int,
     *                }
     */
    public function info(): array
    {
        return [
            'resolved' => null !== $this->resolveResult,
            'body_downloaded' => $this->bodyDownloaded,
            'response' => $this->httpResponse,
            'status' => (int) $this->httpResponse->getInfo('http_code'),
        ];
    }

    public function cancel(): void
    {
        $this->httpResponse->cancel();
        $this->resolveResult = false;
    }

    /**
     * @throws NetworkException
     * @throws HttpException
     */
    public function getHeaders(): array
    {
        $this->resolve();

        return $this->httpResponse->getHeaders(false);
    }

    /**
     * @throws NetworkException
     * @throws HttpException
     */
    public function getContent(): string
    {
        $this->resolve();

        try {
            return $this->httpResponse->getContent(false);
        } finally {
            $this->bodyDownloaded = true;
        }
    }

    /**
     * @throws NetworkException
     * @throws HttpException
     */
    public function toArray(): array
    {
        $this->resolve();

        try {
            return $this->httpResponse->toArray(false);
        } finally {
            $this->bodyDownloaded = true;
        }
    }

    public function getStatusCode(): int
    {
        return $this->httpResponse->getStatusCode();
    }

    /**
     * @throws NetworkException
     * @throws HttpException
     */
    public function toStream(): ResultStream
    {
        $this->resolve();

        if (\is_callable([$this->httpResponse, 'toStream'])) {
            return new ResponseBodyResourceStream($this->httpResponse->toStream());
        }

        if ($this->streamStarted) {
            throw new RuntimeException('Can not create a ResultStream because the body started being downloaded. The body was started to be downloaded in Response::wait()');
        }

        try {
            return new ResponseBodyStream($this->httpClient->stream($this->httpResponse));
        } finally {
            $this->bodyDownloaded = true;
        }
    }

    private function defineResolveStatus(): void
    {
        try {
            $statusCode = $this->httpResponse->getStatusCode();
        } catch (TransportExceptionInterface $e) {
            $this->resolveResult = [NetworkException::class, ['Could not contact remote server.', 0, $e]];

            return;
        }

        if (500 <= $statusCode) {
            $this->resolveResult = [ServerException::class, [$this->httpResponse]];

            return;
        }

        if (400 <= $statusCode) {
            $this->resolveResult = [ClientException::class, [$this->httpResponse]];

            return;
        }

        if (300 <= $statusCode) {
            $this->resolveResult = [RedirectionException::class, [$this->httpResponse]];

            return;
        }

        $this->resolveResult = true;
    }

    private function getResolveStatus(): bool
    {
        if (\is_bool($this->resolveResult)) {
            return $this->resolveResult;
        }

        if (\is_array($this->resolveResult)) {
            [$class, $args] = $this->resolveResult;
            /** @psalm-suppress PropertyTypeCoercion */
            $this->resolveResult = new $class(...$args);
        }

        if ($this->resolveResult instanceof HttpException) {
            /** @var int $code */
            $code = $this->httpResponse->getInfo('http_code');
            /** @var string $url */
            $url = $this->httpResponse->getInfo('url');
            $this->logger->error(sprintf('HTTP %d returned for "%s".', $code, $url), [
                'aws_code' => $this->resolveResult->getAwsCode(),
                'aws_message' => $this->resolveResult->getAwsMessage(),
                'aws_type' => $this->resolveResult->getAwsType(),
                'aws_detail' => $this->resolveResult->getAwsDetail(),
            ]);
        }

        if ($this->resolveResult instanceof Exception) {
            $this->didThrow = true;

            throw $this->resolveResult;
        }

        throw new RuntimeException('Unexpected resolve state');
    }
}
