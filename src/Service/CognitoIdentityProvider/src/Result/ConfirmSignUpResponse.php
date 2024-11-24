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
     * You can automatically sign users in with the one-time password that they provided in a successful `ConfirmSignUp`
     * request. To do this, pass the `Session` parameter from the `ConfirmSignUp` response in the `Session` parameter of an
     * InitiateAuth [^1] or AdminInitiateAuth [^2] request.
     *
     * [^1]: https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_InitiateAuth.html
     * [^2]: https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminInitiateAuth.html
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
