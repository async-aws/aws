<?php

namespace AsyncAws\Iam\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because it attempted to delete a resource that has attached subordinate entities. The error
 * message describes these entities.
 */
final class DeleteConflictException extends ClientException
{
}
