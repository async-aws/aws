<?php

namespace AsyncAws\DynamoDb\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The operation conflicts with the resource's availability. For example:
 *
 * - You attempted to recreate an existing table.
 * - You tried to delete a table currently in the `CREATING` state.
 * - You tried to update a resource that was already being updated.
 *
 * When appropriate, wait for the ongoing update to complete and attempt the request again.
 */
final class ResourceInUseException extends ClientException
{
}
