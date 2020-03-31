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
use AsyncAws\Core\Exception\RuntimeException;
use AsyncAws\Core\Stream\ResponseBodyResourceStream;
use AsyncAws\Core\Stream\ResponseBodyStream;
use AsyncAws\Core\Stream\ResultStream;
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
     * Null means that the result has never been resolved.
     *
     * @var bool|NetworkException|HttpException|null
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

    public function __construct(ResponseInterface $response, HttpClientInterface $httpClient)
    {
        $this->httpResponse = $response;
        $this->httpClient = $httpClient;
    }

    public function __destruct()
    {
        if (null === $this->resolveResult) {
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
            if ($this->resolveResult instanceof \Exception) {
                throw $this->resolveResult;
            }

            if (\is_bool($this->resolveResult)) {
                return $this->resolveResult;
            }

            throw new RuntimeException('Unexpected resolve state');
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

            return $this->handleStatus();
        } catch (TransportExceptionInterface $e) {
            throw $this->resolveResult = new NetworkException('Could not contact remote server.', 0, $e);
        }
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
    final public static function multiplex(iterable $responses, float $timeout = null, bool $downloadBody = false): iterable
    {
        /** @var self[] $responseMap */
        $responseMap = [];
        $indexMap = [];
        $httpResponses = [];
        $httpClient = null;
        foreach ($responses as $index => $response) {
            if (null !== $response->resolveResult && (!$downloadBody || $response->bodyDownloaded)) {
                yield $index => $response;

                continue;
            }

            if (null === $httpClient) {
                $httpClient = $response->httpClient;
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

        try {
            foreach ($httpClient->stream($httpResponses, $timeout) as $httpResponse => $chunk) {
                if ($chunk->isTimeout()) {
                    // Receiving a timeout mean all responses are inactive.
                    break;
                }

                $response = $responseMap[$hash = \spl_object_id($httpResponse)] ?? null;
                // Check if null, just in case symfony yield an unexpected response.
                if (null === $response) {
                    continue;
                }

                if (!$response->streamStarted && '' !== $chunk->getContent()) {
                    $response->streamStarted = true;
                }

                // index could be null if already yield
                $index = $indexMap[$hash] ?? null;
                if ($chunk->isLast()) {
                    $response->bodyDownloaded = true;
                    if (null !== $index && $downloadBody) {
                        unset($indexMap[$hash]);
                        yield $index => $response;
                    }
                }
                if ($chunk->isFirst()) {
                    try {
                        // call handleStatus to set internal state: `resolveResult` and ignore errors
                        $response->handleStatus();
                    } catch (Exception $e) {
                    }
                    if (null !== $index && !$downloadBody) {
                        unset($indexMap[$hash]);
                        yield $index => $response;

                        if (empty($indexMap)) {
                            // early exit if all statusCode are known. We don't have to wait for all responses bodies
                            return;
                        }
                    }
                }
            }
        } catch (TransportExceptionInterface $e) {
            throw new NetworkException('Could not contact remote server.', 0, $e);
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

        $content = $this->httpResponse->getContent(false);
        $this->bodyDownloaded = true;

        return $content;
    }

    /**
     * @throws NetworkException
     * @throws HttpException
     */
    public function toArray(): array
    {
        $this->resolve();

        $content = $this->httpResponse->toArray(false);
        $this->bodyDownloaded = true;

        return $content;
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
            throw new RuntimeException('Can not create a ResultStream because the body started being downloaded. Do not call Result::multiplex');
        }

        return new ResponseBodyStream($this->httpClient->stream($this->httpResponse));
    }

    private function handleStatus(): bool
    {
        try {
            $statusCode = $this->httpResponse->getStatusCode();
        } catch (TransportExceptionInterface $e) {
            throw $this->resolveResult = new NetworkException('Could not contact remote server.', 0, $e);
        }

        if (500 <= $statusCode) {
            throw $this->resolveResult = new ServerException($this->httpResponse);
        }

        if (400 <= $statusCode) {
            throw $this->resolveResult = new ClientException($this->httpResponse);
        }

        if (300 <= $statusCode) {
            throw $this->resolveResult = new RedirectionException($this->httpResponse);
        }

        return $this->resolveResult = true;
    }
}
