<?php

namespace AsyncAws\SecretsManager\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Secrets Manager can't decrypt the protected secret text using the provided KMS key.
 */
final class DecryptionFailureException extends ClientException
{
}
