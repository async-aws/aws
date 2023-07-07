<?php

namespace AsyncAws\Kms\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because the specified KMS key is not enabled.
 */
final class DisabledException extends ClientException
{
}
