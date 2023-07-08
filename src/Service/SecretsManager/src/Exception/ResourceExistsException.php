<?php

namespace AsyncAws\SecretsManager\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * A resource with the ID you requested already exists.
 */
final class ResourceExistsException extends ClientException
{
}
