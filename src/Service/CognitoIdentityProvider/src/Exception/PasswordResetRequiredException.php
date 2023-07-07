<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * This exception is thrown when a password reset is required.
 */
final class PasswordResetRequiredException extends ClientException
{
}
