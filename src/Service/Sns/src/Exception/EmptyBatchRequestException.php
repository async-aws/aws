<?php

namespace AsyncAws\Sns\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The batch request doesn't contain any entries.
 */
final class EmptyBatchRequestException extends ClientException
{
}
