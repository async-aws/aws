<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * This exception is thrown when a user exceeds the limit for a requested Amazon Web Services resource.
 */
final class LimitExceededException extends ClientException
{
}
