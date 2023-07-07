<?php

namespace AsyncAws\SecretsManager\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Secrets Manager can't encrypt the protected secret text using the provided KMS key. Check that the KMS key is
 * available, enabled, and not in an invalid state. For more information, see Key state: Effect on your KMS key [^1].
 *
 * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/key-state.html
 */
final class EncryptionFailureException extends ClientException
{
}
