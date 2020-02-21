<?php

declare(strict_types=1);

namespace AsyncAws\Core;

use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\Core\Exception\Http\HttpException;
use AsyncAws\Core\Exception\Http\NetworkException;
use AsyncAws\Core\Exception\Http\RedirectionException;
use AsyncAws\Core\Exception\Http\ServerException;
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
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * A Result can be resolved many times. This boolean is true if the result
     * has been resolved at least once.
     *
     * @var bool
     */
    private $isResolved = false;

    public function __construct(ResponseInterface $response, HttpClientInterface $httpClient, AbstractApi $awsClient = null, $request = null)
    {
        $this->response = $response;
        $this->httpClient = $httpClient;
        $this->awsClient = $awsClient;
        $this->input = $request;
    }

    public function __destruct()
    {
        if (false === $this->isResolved) {
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
        if (!isset($this->response)) {
            return true;
        }

        try {
            if (null !== $timeout && $this->httpClient->stream($this->response, $timeout)->current()->isTimeout()) {
                return false;
            }
            $statusCode = $this->response->getStatusCode();
        } catch (TransportExceptionInterface $e) {
            // When a network error occurs
            $this->isResolved = true;

            throw new NetworkException('Could not contact remote server.', 0, $e);
        }

        $this->isResolved = true;
        if (500 <= $statusCode) {
            throw new ServerException($this->response);
        }

        if (400 <= $statusCode) {
            throw new ClientException($this->response);
        }

        if (300 <= $statusCode) {
            throw new RedirectionException($this->response);
        }

        return true;
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
        if (!isset($this->response)) {
            return [
                'resolved' => $this->isResolved,
            ];
        }

        return [
            'resolved' => $this->isResolved,
            'response' => $this->response,
            'status' => (int) $this->response->getInfo('http_code'),
        ];
    }

    final public function cancel(): void
    {
        if (!isset($this->response)) {
            return;
        }

        $this->response->cancel();
    }

    final protected function initialize(): void
    {
        if (!isset($this->response)) {
            return;
        }
        $this->resolve();
        $this->populateResult($this->response, $this->httpClient);
        unset($this->response);
        if (isset($this->httpClient)) {
            unset($this->httpClient);
        }
    }

    protected function populateResult(ResponseInterface $response, HttpClientInterface $httpClient): void
    {
    }
}
