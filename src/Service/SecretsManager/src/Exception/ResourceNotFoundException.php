<?php

namespace AsyncAws\SecretsManager\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Secrets Manager can't find the resource that you asked for.
 */
final class ResourceNotFoundException extends ClientException
{
}
