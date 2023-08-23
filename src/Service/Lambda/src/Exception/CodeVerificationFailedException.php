<?php

namespace AsyncAws\Lambda\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The code signature failed one or more of the validation checks for signature mismatch or expiry, and the code signing
 * policy is set to ENFORCE. Lambda blocks the deployment.
 */
final class CodeVerificationFailedException extends ClientException
{
    /**
     * @var string|null
     */
    private $type;

    public function getType(): ?string
    {
        return $this->type;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        $this->type = isset($data['Type']) ? (string) $data['Type'] : null;
    }
}
