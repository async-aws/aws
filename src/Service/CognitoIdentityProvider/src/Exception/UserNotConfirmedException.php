<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * This exception is thrown when a user isn't confirmed successfully.
 */
final class UserNotConfirmedException extends ClientException
{
}
