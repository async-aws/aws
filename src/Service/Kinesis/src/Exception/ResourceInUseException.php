<?php

namespace AsyncAws\Kinesis\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The resource is not available for this operation. For successful operation, the resource must be in the `ACTIVE`
 * state.
 */
final class ResourceInUseException extends ClientException
{
}
