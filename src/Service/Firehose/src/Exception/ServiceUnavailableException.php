<?php

namespace AsyncAws\Firehose\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The service is unavailable. Back off and retry the operation. If you continue to see the exception, throughput limits
 * for the Firehose stream may have been exceeded. For more information about limits and how to request an increase, see
 * Amazon Firehose Limits [^1].
 *
 * [^1]: https://docs.aws.amazon.com/firehose/latest/dev/limits.html
 */
final class ServiceUnavailableException extends ClientException
{
}
