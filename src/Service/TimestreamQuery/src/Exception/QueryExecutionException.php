<?php

namespace AsyncAws\TimestreamQuery\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Timestream was unable to run the query successfully.
 */
final class QueryExecutionException extends ClientException
{
}
