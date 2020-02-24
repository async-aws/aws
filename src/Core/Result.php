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
 * The result promise is always returned from every API call. Remember to call `resolve()` to
 * make sure the request is actually sent.
 */
class Result
{
    /**
     * @var AbstractApi|null
     */
    protected $awsClient;

    /**
     * Input used to build the API request that generate this Result.
     *
     * @var object|null
     */
    protected $input;

    /**
     * @var ResponseInterface|null
     */
    private $response;

    /**
     * @var self[]
     */
    private $prefetchResults = [];

    /**
     * @var HttpClientInterface|null
     */
    private $httpClient;

    /**
     * A Result can be resolved many times. This variable contains the last resolve result.
     *
     * @var bool|NetworkException|HttpException|null
     */
    private $resolveResult;

    public function __construct(ResponseInterface $response, HttpClientInterface $httpClient, AbstractApi $awsClient = null, $request = null)
    {
        $this->response = $response;
        $this->httpClient = $httpClient;
        $this->awsClient = $awsClient;
        $this->input = $request;
    }

    public function __destruct()
    {
        while (!empty($this->prefetchResponses)) {
            array_shift($this->prefetchResponses)->cancel();
        }

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

        if (null === $this->response || null === $this->httpClient) {
            return true;
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
        if (null === $this->response) {
            return [
                'resolved' => null !== $this->resolveResult,
            ];
        }

        return [
            'resolved' => null !== $this->resolveResult,
            'response' => $this->response,
            'status' => (int) $this->response->getInfo('http_code'),
        ];
    }

    final public function cancel(): void
    {
        if (null === $this->response) {
            return;
        }

        $this->response->cancel();
        $this->resolveResult = false;
        $this->response = null;
    }

    final protected function registerPrefetch(self $result): void
    {
        $this->prefetchResults[spl_object_id($result)] = $result;
    }

    final protected function unregisterPrefetch(self $result): void
    {
        unset($this->prefetchResults[spl_object_id($result)]);
    }

    final protected function initialize(): void
    {
        if (null === $this->response || null === $this->httpClient) {
            return;
        }

        $this->resolve();
        $this->populateResult($this->response, $this->httpClient);
        $this->response = null;
        $this->httpClient = null;
    }

    protected function populateResult(ResponseInterface $response, HttpClientInterface $httpClient): void
    {
    }
}
