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
     * @var ResponseInterface|null
     */
    private $response;

    /**
     * @var HttpClientInterface|null
     */
    private $httpClient;

    public function __construct(ResponseInterface $response, HttpClientInterface $httpClient = null)
    {
        $this->response = $response;
        $this->httpClient = $httpClient;
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

    protected function populateResult(ResponseInterface $response, ?HttpClientInterface $httpClient): void
    {
    }

    /**
     * Make sure the actual request is executed.
     *
     * @throws Exception
     */
    final public function resolve()
    {
        if (null === $this->response) {
            return;
        }

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
    }

    /**
     * Returns info on the current request
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
        if (null === $this->response) {
            return;
        }

        $this->response->cancel();
    }

    final protected function xmlValueOrNull(\SimpleXMLElement $xml, string $type)
    {
        if (0 === $xml->count()) {
            return null;
        }

        $value = $xml->__toString();

        // Return the correct type
        switch ($type) {
            case '\DateTimeImmutable':
                return new \DateTimeImmutable($value);
            case 'int':
                return (int) $value;
            case 'bool':
                return 'true' === $value;
            case 'string':
            default:
                return $value;
        }
    }
}
