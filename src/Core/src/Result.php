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
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class Result implements ResultInterface
{
    /**
     * @var ApiInterface|null
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
     * @var ResultInterface[]
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

    public function __construct(ResponseInterface $response, HttpClientInterface $httpClient, ApiInterface $awsClient = null, $request = null)
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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

    /**
     * {@inheritdoc}
     */
    final public function cancel(): void
    {
        if (null === $this->response) {
            return;
        }

        $this->response->cancel();
        $this->resolveResult = false;
        $this->response = null;
    }

    final protected function registerPrefetch(ResultInterface $result): void
    {
        $this->prefetchResults[spl_object_id($result)] = $result;
    }

    final protected function unregisterPrefetch(ResultInterface $result): void
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
