<?php

namespace AsyncAws\Kinesis\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request failed validation. For example, a parameter value is not valid, a combination of parameters is not
 * supported, or the operation is not supported for the stream's capacity mode. The error message describes the specific
 * reason.
 */
final class ValidationException extends ClientException
{
}
