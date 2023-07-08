<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * This exception is thrown when the user has made too many failed attempts for a given action, such as sign-in.
 */
final class TooManyFailedAttemptsException extends ClientException
{
}
