<?php

namespace AsyncAws\RdsDataService\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * There was an error in processing the SQL statement.
 */
final class DatabaseErrorException extends ClientException
{
}
