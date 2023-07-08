<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * This exception is thrown if two or more modifications are happening concurrently.
 */
final class ConcurrentModificationException extends ClientException
{
}
