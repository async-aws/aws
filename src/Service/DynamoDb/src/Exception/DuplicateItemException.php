<?php

namespace AsyncAws\DynamoDb\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * There was an attempt to insert an item with the same primary key as an item that already exists in the DynamoDB
 * table.
 */
final class DuplicateItemException extends ClientException
{
}
