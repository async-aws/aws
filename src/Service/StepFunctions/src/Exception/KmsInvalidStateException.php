<?php

namespace AsyncAws\StepFunctions\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\StepFunctions\Enum\KmsKeyState;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The KMS key is not in valid state, for example: Disabled or Deleted.
 */
final class KmsInvalidStateException extends ClientException
{
    /**
     * Current status of the KMS; key. For example: `DISABLED`, `PENDING_DELETION`, `PENDING_IMPORT`, `UNAVAILABLE`,
     * `CREATING`.
     *
     * @var KmsKeyState::*|string|null
     */
    private $kmsKeyState;

    /**
     * @return KmsKeyState::*|string|null
     */
    public function getKmsKeyState(): ?string
    {
        return $this->kmsKeyState;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        $this->kmsKeyState = isset($data['kmsKeyState']) ? (string) $data['kmsKeyState'] : null;
    }
}
