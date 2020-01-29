<?php

declare(strict_types=1);

namespace WorkingTitle\Aws;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use WorkingTitle\Aws\Exception\Exception;

/**
 * @template T
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

    private function __construct()
    {
    }

    /**
     * @psalm-param class-string<T> $resultClass
     */
    public static function create(ResponseInterface $response, string $resultClass): self
    {
        $result = new self();
        $result->response = $response;

        return $result;
    }

    /**
     * Make sure the actual request is executed. This will return some result class or throw an exception.
     * @return T|null
     *
     * @throws Exception
     */
    public function resolve(bool $return = true)
    {
        try {
            $statusCode = $this->response->getStatusCode();
            $headers = $this->response->getHeaders(true);
            $content = $this->response->getContent(true);
        } catch (TransportExceptionInterface $e) {
            // When a network error occurs

        }  catch (RedirectionExceptionInterface $e) {
            // On a 3xx and the "max_redirects" option has been reached

        }  catch (ClientExceptionInterface $e) {
            // On a 4xx

        }  catch (ServerExceptionInterface $e) {
            // On a 5xx

        }

        if (!$return) {
            return null;
        }

        try {
            $class = $this->resultClass;
            return new $class($content, $headers, $statusCode);
        } catch (\Throwable $e) {
            // throw fatal exception of some kind
        }
    }
}
