<?php

namespace AsyncAws\Lambda\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\Lambda\Enum\ThrottleReason;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The request throughput limit was exceeded.
 */
final class TooManyRequestsException extends ClientException
{
    /**
     * The number of seconds the caller should wait before retrying.
     */
    private $retryAfterSeconds;

    private $type;

    private $reason;

    /**
     * @return ThrottleReason::*|null
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function getRetryAfterSeconds(): ?string
    {
        return $this->retryAfterSeconds;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $headers = $response->getHeaders();

        $this->retryAfterSeconds = $headers['retry-after'][0] ?? null;

        $data = $response->toArray(false);

        $this->type = isset($data['Type']) ? (string) $data['Type'] : null;
        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
        $this->reason = isset($data['Reason']) ? (string) $data['Reason'] : null;
    }
}
