<?php

namespace AsyncAws\DynamoDb\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The operation conflicts with the resource's availability. For example, you attempted to recreate an existing table,
 * or tried to delete a table currently in the `CREATING` state.
 */
final class ResourceInUseException extends ClientException
{
}
