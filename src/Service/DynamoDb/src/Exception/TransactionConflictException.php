<?php

namespace AsyncAws\DynamoDb\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Operation was rejected because there is an ongoing transaction for the item.
 */
final class TransactionConflictException extends ClientException
{
}
