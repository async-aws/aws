<?php

namespace AsyncAws\ImageBuilder\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * You have exceeded the permitted request rate for the specific operation.
 */
final class CallRateLimitExceededException extends ClientException
{
}
