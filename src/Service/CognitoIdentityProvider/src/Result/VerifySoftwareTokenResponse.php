<?php

namespace AsyncAws\CognitoIdentityProvider\Result;

use AsyncAws\CognitoIdentityProvider\Enum\VerifySoftwareTokenResponseType;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class VerifySoftwareTokenResponse extends Result
{
    /**
     * Amazon Cognito can accept or reject the code that you provide. This response parameter indicates the success of TOTP
     * verification. Some reasons that this operation might return an error are clock skew on the user's device and
     * excessive retries.
     *
     * @var VerifySoftwareTokenResponseType::*|string|null
     */
    private $status;

    /**
     * This session ID satisfies an `MFA_SETUP` challenge. Supply the session ID in your challenge response.
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
     * @return VerifySoftwareTokenResponseType::*|string|null
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
