<?php

namespace AsyncAws\RdsDataService\Exception;

use AsyncAws\Core\Exception\Http\ServerException;

/**
 * The writer instance in the DB cluster isn't available.
 */
final class DatabaseUnavailableException extends ServerException
{
}
