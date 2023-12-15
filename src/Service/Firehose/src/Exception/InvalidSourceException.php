<?php

namespace AsyncAws\Firehose\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Only requests from CloudWatch Logs are supported when CloudWatch Logs decompression is enabled.
 */
final class InvalidSourceException extends ClientException
{
}
