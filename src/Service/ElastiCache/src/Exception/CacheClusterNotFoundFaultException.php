<?php

namespace AsyncAws\ElastiCache\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The requested cluster ID does not refer to an existing cluster.
 */
final class CacheClusterNotFoundFaultException extends ClientException
{
}
