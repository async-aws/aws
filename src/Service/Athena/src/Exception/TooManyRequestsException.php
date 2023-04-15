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

        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
        $this->reason = isset($data['Reason']) ? (string) $data['Reason'] : null;
    }
}
