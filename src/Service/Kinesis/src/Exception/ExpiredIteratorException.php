<?php

namespace AsyncAws\Kinesis\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The provided iterator exceeds the maximum age allowed.
 */
final class ExpiredIteratorException extends ClientException
{
}
