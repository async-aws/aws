<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * This exception is thrown when WAF doesn't allow your request based on a web ACL that's associated with your user
 * pool.
 */
final class ForbiddenException extends ClientException
{
}
