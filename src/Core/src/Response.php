<?php

declare(strict_types=1);

namespace AsyncAws\Core;

use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\Core\Exception\Http\HttpException;
use AsyncAws\Core\Exception\Http\NetworkException;
use AsyncAws\Core\Exception\Http\RedirectionException;
use AsyncAws\Core\Exception\Http\ServerException;
use AsyncAws\Core\Exception\RuntimeException;
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
    private $response;

    private $httpClient;

    /**
     * A Result can be resolved many times. This variable contains the last resolve result.
     *
     * @var bool|NetworkException|HttpException|null
     */
    private $resolveResult;

    public function __construct(ResponseInterface $response, HttpClientInterface $httpClient)
    {
        $this->response = $response;
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
     * @param float|null $timeout Duration in seconds before aborting. When null wait until the end of execution.
     *
     * @return bool whether the request is executed or not
     *
     * @throws NetworkException
     * @throws HttpException
     */
    final public function resolve(?float $timeout = null): bool
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
            foreach ($this->httpClient->stream($this->response, $timeout) as $chunk) {
                if ($chunk->isTimeout()) {
                    return false;
                }
                if ($chunk->isFirst()) {
                    break;
                }
            }

            $statusCode = $this->response->getStatusCode();
        } catch (TransportExceptionInterface $e) {
            throw $this->resolveResult = new NetworkException('Could not contact remote server.', 0, $e);
        }

        if (500 <= $statusCode) {
            throw $this->resolveResult = new ServerException($this->response);
        }

        if (400 <= $statusCode) {
            throw $this->resolveResult = new ClientException($this->response);
        }

        if (300 <= $statusCode) {
            throw $this->resolveResult = new RedirectionException($this->response);
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
    final public function info(): array
    {
        return [
            'resolved' => null !== $this->resolveResult,
            'response' => $this->response,
            'status' => (int) $this->response->getInfo('http_code'),
        ];
    }

    final public function cancel(): void
    {
        $this->response->cancel();
        $this->resolveResult = false;
    }

    final public function getHeaders(): array
    {
        $this->resolve();

        return $this->response->getHeaders(false);
    }

    final public function getContent(): string
    {
        $this->resolve();

        return $this->response->getContent(false);
    }

    final public function toArray(): array
    {
        $this->resolve();

        return $this->response->toArray(false);
    }

    final public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    final public function toStream(): StreamableBody
    {
        return new StreamableBody($this->httpClient->stream($this->response));
    }
}
