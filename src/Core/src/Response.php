<?php

declare(strict_types=1);

namespace AsyncAws\Core;

use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\Core\Exception\Http\HttpException;
use AsyncAws\Core\Exception\Http\NetworkException;
use AsyncAws\Core\Exception\Http\RedirectionException;
use AsyncAws\Core\Exception\Http\ServerException;
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
            return $this->handleResolvedStatus();
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

            return $this->handleUnresolvedStatus();
        } catch (TransportExceptionInterface $e) {
            throw $this->resolveResult = new NetworkException('Could not contact remote server.', 0, $e);
        }
    }

    /**
     * Make sure all provided requests are executed.
     *
     * @param self[]     $responses
     * @param float|null $timeout   Duration in seconds before aborting. When null wait
     *                              until the end of execution. Using 0 means non-blocking
     *
     * @return iterable<self, bool> whether the request is executed or not
     *
     * @throws NetworkException
     * @throws HttpException
     */
    final public static function resolveAll(iterable $responses, float $timeout = null): iterable
    {
        /** @var self[] $responseMap */
        $responseMap = [];
        $httpResponses = [];
        $httpClient = null;
        foreach ($responses as $response) {
            if (null !== $response->resolveResult) {
                yield $response => $response->handleResolvedStatus();

                continue;
            }

            if (null === $httpClient) {
                $httpClient = $response->httpClient;
            }
            $httpResponses[] = $response->httpResponse;
            $responseMap[\spl_object_id($response->httpResponse)] = $response;
        }

        // response is empty
        if (null === $httpClient) {
            return;
        }

        try {
            foreach ($httpClient->stream($httpResponses, $timeout) as $httpResponse => $chunk) {
                if ($chunk->isTimeout()) {
                    continue;
                }
                if ($chunk->isFirst()) {
                    $response = $responseMap[\spl_object_id($httpResponse)] ?? null;

                    if (null !== $response) {
                        yield $response => $response->handleUnresolvedStatus();
                    }
                    unset($responseMap[\spl_object_id($httpResponse)]);
                    if (empty($responseMap)) {
                        // early exit if all statusCode are known. We don't have to wait for all responses bodies
                        return;
                    }
                }
            }
        } catch (TransportExceptionInterface $e) {
            throw new NetworkException('Could not contact remote server.', 0, $e);
        }

        foreach ($responseMap as $response) {
            yield $response => false;
        }
    }

    /**
     * Returns info on the current request.
     *
     * @return array{
     *                resolved: bool,
     *                response?: ?ResponseInterface,
     *                status?: int
     *                }
     */
    public function info(): array
    {
        return [
            'resolved' => null !== $this->resolveResult,
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

        return $this->httpResponse->getContent(false);
    }

    /**
     * @throws NetworkException
     * @throws HttpException
     */
    public function toArray(): array
    {
        $this->resolve();

        return $this->httpResponse->toArray(false);
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

        return new ResponseBodyStream($this->httpClient->stream($this->httpResponse));
    }

    private function handleResolvedStatus(): bool
    {
        if ($this->resolveResult instanceof \Exception) {
            throw $this->resolveResult;
        }

        if (\is_bool($this->resolveResult)) {
            return $this->resolveResult;
        }

        throw new RuntimeException('Unexpected resolve state');
    }

    private function handleUnresolvedStatus(): bool
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
