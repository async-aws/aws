<?php

namespace AsyncAws\Sns\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Indicates that the user has been denied access to the requested resource.
 */
final class AuthorizationErrorException extends ClientException
{
}
