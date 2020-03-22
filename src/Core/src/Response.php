<?php

declare(strict_types=1);

namespace AsyncAws\Core;

use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\Core\Exception\Http\HttpException;
use AsyncAws\Core\Exception\Http\NetworkException;
use AsyncAws\Core\Exception\Http\RedirectionException;
use AsyncAws\Core\Exception\Http\ServerException;
use AsyncAws\Core\Exception\RuntimeException;
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

    public function getHeaders(): array
    {
        $this->resolve();

        return $this->httpResponse->getHeaders(false);
    }

    public function getContent(): string
    {
        $this->resolve();

        return $this->httpResponse->getContent(false);
    }

    public function toArray(): array
    {
        $this->resolve();

        return $this->httpResponse->toArray(false);
    }

    public function getStatusCode(): int
    {
        return $this->httpResponse->getStatusCode();
    }

    public function toStream(): ResultStream
    {
        return new ResponseBodyStream($this->httpClient->stream($this->httpResponse));
    }
}
