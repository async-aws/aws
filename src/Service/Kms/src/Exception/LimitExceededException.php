<?php

namespace AsyncAws\Kms\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because a length constraint or quota was exceeded. For more information, see Quotas [^1] in
 * the *Key Management Service Developer Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/limits.html
 */
final class LimitExceededException extends ClientException
{
}
