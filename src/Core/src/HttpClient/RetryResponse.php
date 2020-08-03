<?php

namespace AsyncAws\Core\HttpClient;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\Chunk\ErrorChunk;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @internal
 */
class RetryResponse implements ResponseInterface
{
    private $client;

    private $method;

    private $url;

    private $options;

    private $maxTryCount;

    private $logger;

    private $tryCount = 1;

    private $initialized = false;

    /**
     * @var ResponseInterface
     */
    private $inner;

    public function __construct(HttpClientInterface $client, string $method, string $url, array $options, LoggerInterface $logger = null, int $maxTryCount = 3)
    {
        $this->client = $client;
        $this->method = $method;
        $this->url = $url;
        $this->options = $options;
        $this->maxTryCount = $maxTryCount;

        $this->logger = $logger ?? new NullLogger();
        $this->inner = $this->client->request($this->method, $this->url, $this->options);
    }

    public function getStatusCode(): int
    {
        $this->initialized or $this->initialize();

        return $this->inner->getStatusCode();
    }

    public function getHeaders(bool $throw = true): array
    {
        $this->initialized or $this->initialize();

        return $this->inner->getHeaders($throw);
    }

    public function getContent(bool $throw = true): string
    {
        $this->initialized or $this->initialize();

        return $this->inner->getContent($throw);
    }

    public function toArray(bool $throw = true): array
    {
        $this->initialized or $this->initialize();

        return $this->inner->toArray($throw);
    }

    public function cancel(): void
    {
        $this->initialized = true;
        $this->inner->cancel();
    }

    public function getInfo(string $type = null)
    {
        $this->initialized or $this->initialize();

        return $this->inner->getInfo($type);
    }

    public static function stream(iterable $responses, float $timeout = null): \Generator
    {
        $wrappedResponses = [];
        $map = new \SplObjectStorage();
        $client = null;

        foreach ($responses as $r) {
            if (!$r instanceof self) {
                throw new \TypeError(sprintf('"%s::stream()" expects parameter 1 to be an iterable of AsyncResponse objects, "%s" given.', static::class, get_debug_type($r)));
            }

            if (null === $client) {
                $client = $r->client;
            } elseif ($r->client !== $client) {
                throw new TransportException('Cannot stream AsyncResponse objects with many clients.');
            }
        }

        if (!$client) {
            return;
        }

        $end = null;
        if (null !== $timeout) {
            $end = \microtime(true) + $timeout;
        }

        while (true) {
            foreach ($responses as $r) {
                $wrappedResponses[] = $r->inner;
                $map[$r->inner] = $r;
            }
            $subTimeout = null;
            if (null !== $end && ($subTimeout = $end - \microtime(true)) <= 0) {
                foreach ($map as $response) {
                    yield $response => new ErrorChunk(0, sprintf('Idle timeout reached for "%s".', $response->getInfo('url')));
                }

                break;
            }

            foreach ($client->stream($wrappedResponses, $subTimeout) as $response => $chunk) {
                /** @var self $r */
                $r = $map[$response];
                if (!$chunk->isTimeout() && $chunk->isFirst()) {
                    if ($r->handleRetry()) {
                        continue 2;
                    }
                }
                yield $r => $chunk;
            }

            break;
        }
    }

    private function initialize(): void
    {
        while (!$this->initialized) {
            $this->handleRetry();
        }
    }

    /**
     * @return bool return true when the request have been retried
     *
     * @throws TransportExceptionInterface
     */
    private function handleRetry(): bool
    {
        if ($this->initialized) {
            return false;
        }

        try {
            if (($status = $this->inner->getStatusCode()) >= 500) {
                if (++$this->tryCount <= $this->maxTryCount) {
                    $this->logger->info('HTTP request failed with status code {statusCode}. Retry the request {attempt}/{maxAttempts}.', [
                        'statusCode' => $status,
                        'attempt' => $this->tryCount,
                        'maxAttempts' => $this->maxTryCount,
                    ]);
                    $this->inner = $this->client->request($this->method, $this->url, $this->options);

                    return true;
                }

                $this->logger->info('HTTP request failed with status code {statusCode}. Stop after {maxAttempts} attempts.', [
                    'statusCode' => $status,
                    'maxAttempts' => $this->maxTryCount,
                ]);
            }
            $this->initialized = true;

            return false;
        } catch (TransportExceptionInterface $e) {
            if (++$this->tryCount <= $this->maxTryCount) {
                $this->logger->info('HTTP request failed with exception {exception}. Retry the request {attempt}/{maxAttempts}.', [
                    'exception' => $e,
                    'attempt' => $this->tryCount,
                    'maxAttempts' => $this->maxTryCount,
                ]);
                $this->inner = $this->client->request($this->method, $this->url, $this->options);

                return true;
            }

            $this->logger->info('HTTP request failed with status code {exception}. Stop after {maxAttempts} attempts.', [
                'exception' => $e,
                'maxAttempts' => $this->maxTryCount,
            ]);
            $this->initialized = true;

            throw $e;
        }
    }
}
