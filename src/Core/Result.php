<?php

declare(strict_types=1);

namespace AsyncAws\Core;

use AsyncAws\Core\Exception\Exception;
use AsyncAws\Core\Exception\Http\ClientException;
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
     * @var array|object|null
     */
    protected $request;

    /**
     * @var ResponseInterface|null
     */
    private $response;

    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    public function __construct(ResponseInterface $response, HttpClientInterface $httpClient, AbstractApi $awsClient = null, $request = null)
    {
        $this->response = $response;
        $this->httpClient = $httpClient;
        $this->awsClient = $awsClient;
        $this->request = $request;
    }

    /**
     * Make sure the actual request is executed.
     *
     * @param float|null $timeout Duration in seconds before aborting. When null wait until the end of execution.
     *
     * @return bool whether the request is executed or not
     *
     * @throws Exception
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
            throw new NetworkException('Could not contact remote server.', 0, $e);
        }

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
                'resolved' => true,
            ];
        }

        return [
            'resolved' => false,
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
        if (null !== $this->httpClient) {
            unset($this->httpClient);
        }
    }

    protected function populateResult(ResponseInterface $response, HttpClientInterface $httpClient): void
    {
    }
}
