<?php

namespace AsyncAws\RdsDataService\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The DB cluster doesn't have a DB instance.
 */
final class DatabaseNotFoundException extends ClientException
{
}
