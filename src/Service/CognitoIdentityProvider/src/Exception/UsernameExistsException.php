<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * This exception is thrown when Amazon Cognito encounters a user name that already exists in the user pool.
 */
final class UsernameExistsException extends ClientException
{
}
