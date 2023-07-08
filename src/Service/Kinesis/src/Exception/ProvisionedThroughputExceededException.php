<?php

namespace AsyncAws\Kinesis\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request rate for the stream is too high, or the requested data is too large for the available throughput. Reduce
 * the frequency or size of your requests. For more information, see Streams Limits [^1] in the *Amazon Kinesis Data
 * Streams Developer Guide*, and Error Retries and Exponential Backoff in Amazon Web Services [^2] in the *Amazon Web
 * Services General Reference*.
 *
 * [^1]: https://docs.aws.amazon.com/kinesis/latest/dev/service-sizes-and-limits.html
 * [^2]: https://docs.aws.amazon.com/general/latest/gr/api-retries.html
 */
final class ProvisionedThroughputExceededException extends ClientException
{
}
