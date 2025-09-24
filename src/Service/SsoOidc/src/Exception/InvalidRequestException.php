<?php

namespace AsyncAws\SsoOidc\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\SsoOidc\Enum\InvalidRequestExceptionReason;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Indicates that something is wrong with the input to the request. For example, a required parameter might be missing
 * or out of range.
 */
final class InvalidRequestException extends ClientException
{
    /**
     * Single error code. For this exception the value will be `invalid_request`.
     *
     * @var string|null
     */
    private $error;

    /**
     * A string that uniquely identifies a reason for the error.
     *
     * @var InvalidRequestExceptionReason::*|null
     */
    private $reason;

    /**
     * Human-readable text providing additional information, used to assist the client developer in understanding the error
     * that occurred.
     *
     * @var string|null
     */
    private $error_description;

    public function getError(): ?string
    {
        return $this->error;
    }

    public function getError_description(): ?string
    {
        return $this->error_description;
    }

    /**
     * @return InvalidRequestExceptionReason::*|null
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        $this->error = isset($data['error']) ? (string) $data['error'] : null;
        $this->reason = isset($data['reason']) ? (string) $data['reason'] : null;
        $this->error_description = isset($data['error_description']) ? (string) $data['error_description'] : null;
    }
}
