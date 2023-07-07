<?php

namespace AsyncAws\Athena\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Indicates that something is wrong with the input to the request. For example, a required parameter may be missing or
 * out of range.
 */
final class InvalidRequestException extends ClientException
{
    /**
     * @var string|null
     */
    private $athenaErrorCode;

    public function getAthenaErrorCode(): ?string
    {
        return $this->athenaErrorCode;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        $this->athenaErrorCode = isset($data['AthenaErrorCode']) ? (string) $data['AthenaErrorCode'] : null;
    }
}
