<?php

namespace AsyncAws\DynamoDb\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The operation tried to access a nonexistent table or index. The resource might not be specified correctly, or its
 * status might not be `ACTIVE`.
 */
final class ResourceNotFoundException extends ClientException
{
}
