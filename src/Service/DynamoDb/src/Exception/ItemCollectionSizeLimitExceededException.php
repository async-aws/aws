<?php

namespace AsyncAws\DynamoDb\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * An item collection is too large. This exception is only returned for tables that have one or more local secondary
 * indexes.
 */
final class ItemCollectionSizeLimitExceededException extends ClientException
{
}
