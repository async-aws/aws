<?php

namespace AsyncAws\Sqs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Two or more batch entries in the request have the same `Id`.
 */
final class BatchEntryIdsNotDistinctException extends ClientException
{
}
