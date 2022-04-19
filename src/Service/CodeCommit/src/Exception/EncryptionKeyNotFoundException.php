<?php

namespace AsyncAws\CodeCommit\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * No encryption key was found.
 */
final class EncryptionKeyNotFoundException extends ClientException
{
}
