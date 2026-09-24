<?php

namespace AsyncAws\ImageBuilder\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * You have exceeded the permitted request rate for the Amazon EC2 APIs that Image Builder calls on your behalf. Retry
 * with an increasing or variable delay between requests.
 */
final class CallRateLimitExceededException extends ClientException
{
}
