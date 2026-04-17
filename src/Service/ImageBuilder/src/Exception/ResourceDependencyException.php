<?php

namespace AsyncAws\ImageBuilder\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * You have attempted to mutate or delete a resource with a dependency that prohibits this action. See the error message
 * for more details.
 */
final class ResourceDependencyException extends ClientException
{
}
