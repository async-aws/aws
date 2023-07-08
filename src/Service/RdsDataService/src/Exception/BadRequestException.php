<?php

namespace AsyncAws\RdsDataService\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * There is an error in the call or in a SQL statement.
 */
final class BadRequestException extends ClientException
{
}
