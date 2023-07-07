<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * This exception is thrown if the provided code doesn't match what the server was expecting.
 */
final class CodeMismatchException extends ClientException
{
}
