<?php

namespace AsyncAws\Athena\Exception;

use AsyncAws\Athena\Enum\ThrottleReason;
use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Indicates that the request was throttled.
 */
final class TooManyRequestsException extends ClientException
{
    /**
     * @var ThrottleReason::*|string|null
     */
    private $reason;

    /**
     * @return ThrottleReason::*|string|null
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        $this->reason = isset($data['Reason']) ? (string) $data['Reason'] : null;
    }
}
