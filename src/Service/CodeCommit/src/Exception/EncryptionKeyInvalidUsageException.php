<?php

namespace AsyncAws\CodeCommit\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * A KMS encryption key was used to try and encrypt or decrypt a repository, but either the repository or the key was
 * not in a valid state to support the operation.
 */
final class EncryptionKeyInvalidUsageException extends ClientException
{
}
