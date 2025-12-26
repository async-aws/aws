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

    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        $this->reason = isset($data['Reason']) ? (!ThrottleReason::exists((string) $data['Reason']) ? ThrottleReason::UNKNOWN_TO_SDK : (string) $data['Reason']) : null;
    }
}
