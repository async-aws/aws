<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * This exception is thrown when a user isn't authorized.
 */
final class NotAuthorizedException extends ClientException
{
}
