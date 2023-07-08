<?php

namespace AsyncAws\Sns\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The ciphertext references a key that doesn't exist or that you don't have access to.
 */
final class KMSAccessDeniedException extends ClientException
{
}
