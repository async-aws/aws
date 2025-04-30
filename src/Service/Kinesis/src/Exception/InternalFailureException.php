<?php

namespace AsyncAws\Kinesis\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The processing of the request failed because of an unknown error, exception, or failure.
 */
final class InternalFailureException extends ClientException
{
}
