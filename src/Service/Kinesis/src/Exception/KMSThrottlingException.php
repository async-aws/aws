<?php

namespace AsyncAws\Kinesis\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was denied due to request throttling. For more information about throttling, see Limits [^1] in the
 * *Amazon Web Services Key Management Service Developer Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/limits.html#requests-per-second
 */
final class KMSThrottlingException extends ClientException
{
}
