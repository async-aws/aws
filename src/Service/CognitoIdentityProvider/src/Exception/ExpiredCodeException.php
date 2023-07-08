<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * This exception is thrown if a code has expired.
 */
final class ExpiredCodeException extends ClientException
{
}
