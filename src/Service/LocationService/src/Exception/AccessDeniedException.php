<?php

namespace AsyncAws\LocationService\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was denied because of insufficient access or permissions. Check with an administrator to verify your
 * permissions.
 */
final class AccessDeniedException extends ClientException
{
}
