<?php

namespace AsyncAws\CognitoIdentityProvider\Result;

use AsyncAws\CognitoIdentityProvider\Enum\VerifySoftwareTokenResponseType;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class VerifySoftwareTokenResponse extends Result
{
    /**
     * The status of the verify software token.
     *
     * @var VerifySoftwareTokenResponseType::*|null
     */
    private $status;

    /**
     * The session that should be passed both ways in challenge-response calls to the service.
     *
     * @var string|null
     */
    private $session;

    public function getSession(): ?string
    {
        $this->initialize();

        return $this->session;
    }

    /**
     * @return VerifySoftwareTokenResponseType::*|null
     */
    public function getStatus(): ?string
    {
        $this->initialize();

        return $this->status;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->status = isset($data['Status']) ? (string) $data['Status'] : null;
        $this->session = isset($data['Session']) ? (string) $data['Session'] : null;
    }
}
