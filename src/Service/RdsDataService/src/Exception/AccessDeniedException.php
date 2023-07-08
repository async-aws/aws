<?php

namespace AsyncAws\RdsDataService\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * You do not have sufficient access to perform this action.
 */
final class AccessDeniedException extends ClientException
{
}
