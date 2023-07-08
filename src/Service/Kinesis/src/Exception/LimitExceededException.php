<?php

namespace AsyncAws\Kinesis\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The requested resource exceeds the maximum number allowed, or the number of concurrent stream requests exceeds the
 * maximum number allowed.
 */
final class LimitExceededException extends ClientException
{
}
