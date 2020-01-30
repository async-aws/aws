<?php

declare(strict_types=1);

namespace AsyncAws\Aws;

use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use AsyncAws\Aws\Exception\ApiResultInstantiationException;
use AsyncAws\Aws\Exception\ClientException;
use AsyncAws\Aws\Exception\Exception;
use AsyncAws\Aws\Exception\NetworkException;
use AsyncAws\Aws\Exception\RedirectionException;
use AsyncAws\Aws\Exception\ServerException;

/**
 * The result promise is always returned from every API call. Remember to call `resolve()` to
 * make sure the request is actually sent.
 *
 * @template TResult
 */
class ResultPromise
{
    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var string
     */
    private $resultClass;

    /**
     * @param class-string<TResult> $resultClass
     */
    public function __construct(ResponseInterface $response, string $resultClass)
    {
        $this->response = $response;
        $this->resultClass = $resultClass;
    }

    /**
     * Make sure the actual request is executed. This will return some result class or throw an exception.
     *
     * @return TResult|null
     *
     * @throws Exception
     */
    public function resolve(bool $return = true)
    {
        try {
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

        if (!$return) {
            return null;
        }

        try {
            $class = $this->resultClass;
            if (ResponseInterface::class === $class) {
                $result = $this->response;
            } else {
                $result = new $class($this->response);
            }

            /* @var TResult $result */
            return $result;
        } catch (\Throwable $e) {
            throw ApiResultInstantiationException::create($this->response, $class, $e);
        }
    }

    public function cancel(): void
    {
        $this->response->cancel();
    }
}
