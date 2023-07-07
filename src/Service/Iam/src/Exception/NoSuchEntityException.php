<?php

namespace AsyncAws\Iam\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because it referenced a resource entity that does not exist. The error message describes the
 * resource.
 */
final class NoSuchEntityException extends ClientException
{
}
