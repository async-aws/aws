<?php

namespace AsyncAws\Lambda\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The code signature failed the integrity check. If the integrity check fails, then Lambda blocks deployment, even if
 * the code signing policy is set to WARN.
 */
final class InvalidCodeSignatureException extends ClientException
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
