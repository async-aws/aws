<?php

namespace AsyncAws\Core\HttpClient;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\Response\ResponseStream;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

class RetryHttpClient implements HttpClientInterface
{
    private $decorated;

    private $maxTryCount;

    private $logger;

    public function __construct(HttpClientInterface $decorated, LoggerInterface $logger = null, int $maxTryCount = 3)
    {
        $this->decorated = $decorated;
        $this->maxTryCount = $maxTryCount;
        $this->logger = $logger;
    }

    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        return new RetryResponse($this->decorated, $method, $url, $options, $this->logger, $this->maxTryCount);
    }

    public function stream($responses, float $timeout = null): ResponseStreamInterface
    {
        if ($responses instanceof RetryResponse) {
            $responses = [$responses];
        } elseif (!is_iterable($responses)) {
            throw new \TypeError(sprintf('"%s()" expects parameter 1 to be an iterable of RetryResponse objects, "%s" given.', __METHOD__, get_debug_type($responses)));
        }

        return new ResponseStream(RetryResponse::stream($responses, $timeout));
    }
}
