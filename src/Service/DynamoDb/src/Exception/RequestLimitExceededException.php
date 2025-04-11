<?php

namespace AsyncAws\DynamoDb\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Throughput exceeds the current throughput quota for your account. Please contact Amazon Web ServicesSupport [^1] to
 * request a quota increase.
 *
 * [^1]: https://aws.amazon.com/support
 */
final class RequestLimitExceededException extends ClientException
{
}
