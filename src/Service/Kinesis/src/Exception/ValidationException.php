<?php

namespace AsyncAws\Kinesis\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Specifies that you tried to invoke this API for a data stream with the on-demand capacity mode. This API is only
 * supported for data streams with the provisioned capacity mode.
 */
final class ValidationException extends ClientException
{
}
