<?php

namespace AsyncAws\RdsDataService\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * There is an error in the call or in a SQL statement. This exception is deprecated.
 */
final class BadRequestException extends ClientException
{
}
