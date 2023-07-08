<?php

namespace AsyncAws\Iam\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because it attempted to create a resource that already exists.
 */
final class EntityAlreadyExistsException extends ClientException
{
}
