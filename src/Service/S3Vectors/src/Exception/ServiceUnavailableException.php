<?php

namespace AsyncAws\S3Vectors\Exception;

use AsyncAws\Core\Exception\Http\ServerException;

/**
 * The service is unavailable. Wait briefly and retry your request. If it continues to fail, increase your waiting time
 * between retries.
 */
final class ServiceUnavailableException extends ServerException
{
}
