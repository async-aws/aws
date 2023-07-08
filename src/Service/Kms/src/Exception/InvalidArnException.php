<?php

namespace AsyncAws\Kms\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because a specified ARN, or an ARN in a key policy, is not valid.
 */
final class InvalidArnException extends ClientException
{
}
