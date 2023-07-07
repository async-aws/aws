<?php

namespace AsyncAws\DynamoDb\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * DynamoDB rejected the request because you retried a request with a different payload but with an idempotent token
 * that was already used.
 */
final class IdempotentParameterMismatchException extends ClientException
{
}
