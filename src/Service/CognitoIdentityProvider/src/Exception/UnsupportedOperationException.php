<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Exception that is thrown when you attempt to perform an operation that isn't enabled for the user pool client.
 */
final class UnsupportedOperationException extends ClientException
{
}
