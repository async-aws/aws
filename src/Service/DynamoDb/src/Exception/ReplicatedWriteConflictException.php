<?php

namespace AsyncAws\DynamoDb\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because one or more items in the request are being modified by a request in another Region.
 */
final class ReplicatedWriteConflictException extends ClientException
{
}
