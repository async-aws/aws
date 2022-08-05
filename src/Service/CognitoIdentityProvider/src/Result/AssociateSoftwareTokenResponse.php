<?php

namespace AsyncAws\CognitoIdentityProvider\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class AssociateSoftwareTokenResponse extends Result
{
    /**
     * A unique generated shared secret code that is used in the TOTP algorithm to generate a one-time code.
     */
    private $secretCode;

    /**
     * The session that should be passed both ways in challenge-response calls to the service. This allows authentication of
     * the user as part of the MFA setup process.
     */
    private $session;

    public function getSecretCode(): ?string
    {
        $this->initialize();

        return $this->secretCode;
    }

    public function getSession(): ?string
    {
        $this->initialize();

        return $this->session;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->secretCode = isset($data['SecretCode']) ? (string) $data['SecretCode'] : null;
        $this->session = isset($data['Session']) ? (string) $data['Session'] : null;
    }
}
