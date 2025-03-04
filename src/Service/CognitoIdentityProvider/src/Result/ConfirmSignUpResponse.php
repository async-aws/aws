<?php

namespace AsyncAws\CognitoIdentityProvider\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * Represents the response from the server for the registration confirmation.
 */
class ConfirmSignUpResponse extends Result
{
    /**
     * A session identifier that you can use to immediately sign in the confirmed user. You can automatically sign users in
     * with the one-time password that they provided in a successful `ConfirmSignUp` request.
     *
     * @var string|null
     */
    private $session;

    public function getSession(): ?string
    {
        $this->initialize();

        return $this->session;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->session = isset($data['Session']) ? (string) $data['Session'] : null;
    }
}
