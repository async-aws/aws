<?php

namespace AsyncAws\CognitoIdentityProvider\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * The response from the server that results from a user's request to retrieve a forgotten password.
 */
class ConfirmForgotPasswordResponse extends Result
{
    protected function populateResult(Response $response): void
    {
    }
}
