<?php

namespace AsyncAws\RdsDataService\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * There are insufficient privileges to make the call.
 */
final class ForbiddenException extends ClientException
{
}
