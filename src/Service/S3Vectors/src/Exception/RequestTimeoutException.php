<?php

namespace AsyncAws\S3Vectors\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request timed out. Retry your request.
 */
final class RequestTimeoutException extends ClientException
{
}
