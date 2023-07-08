<?php

namespace AsyncAws\Sns\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The batch request contains more entries than permissible.
 */
final class TooManyEntriesInBatchRequestException extends ClientException
{
}
