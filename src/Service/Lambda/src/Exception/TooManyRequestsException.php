<?php

namespace AsyncAws\Lambda\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\Lambda\Enum\ThrottleReason;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The request throughput limit was exceeded. For more information, see Lambda quotas [^1].
 *
 * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/gettingstarted-limits.html#api-requests
 */
final class TooManyRequestsException extends ClientException
{
    /**
     * The number of seconds the caller should wait before retrying.
     *
     * @var string|null
     */
    private $retryAfterSeconds;

    /**
     * @var string|null
     */
    private $type;

    /**
     * @var ThrottleReason::*|null
     */
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
        $this->reason = isset($data['Reason']) ? (string) $data['Reason'] : null;
    }
}
