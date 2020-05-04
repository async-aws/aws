<?php

namespace AsyncAws\CognitoIdentityProvider\Result;

use AsyncAws\CognitoIdentityProvider\Enum\VerifySoftwareTokenResponseType;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class VerifySoftwareTokenResponse extends Result
{
    /**
     * The status of the verify software token.
     */
    private $Status;

    /**
     * The session which should be passed both ways in challenge-response calls to the service.
     */
    private $Session;

    public function getSession(): ?string
    {
        $this->initialize();

        return $this->Session;
    }

    /**
     * @return VerifySoftwareTokenResponseType::*|null
     */
    public function getStatus(): ?string
    {
        $this->initialize();

        return $this->Status;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->Status = isset($data['Status']) ? (string) $data['Status'] : null;
        $this->Session = isset($data['Session']) ? (string) $data['Session'] : null;
    }
}
