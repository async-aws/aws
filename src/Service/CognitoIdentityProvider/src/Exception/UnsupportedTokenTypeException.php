<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Exception that is thrown when an unsupported token is passed to an operation.
 */
final class UnsupportedTokenTypeException extends ClientException
{
}
