<?php

namespace AsyncAws\Sns\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The batch request contains more entries than permissible (more than 10).
 */
final class TooManyEntriesInBatchRequestException extends ClientException
{
}
