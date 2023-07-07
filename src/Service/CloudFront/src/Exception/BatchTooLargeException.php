<?php

namespace AsyncAws\CloudFront\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Invalidation batch specified is too large.
 */
final class BatchTooLargeException extends ClientException
{
}
