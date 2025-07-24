<?php

namespace AsyncAws\StepFunctions\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\StepFunctions\Enum\ValidationExceptionReason;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The input does not satisfy the constraints specified by an Amazon Web Services service.
 */
final class ValidationException extends ClientException
{
    /**
     * The input does not satisfy the constraints specified by an Amazon Web Services service.
     *
     * @var ValidationExceptionReason::*|string|null
     */
    private $reason;

    /**
     * @return ValidationExceptionReason::*|string|null
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        $this->reason = isset($data['reason']) ? (string) $data['reason'] : null;
    }
}
