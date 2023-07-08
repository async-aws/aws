<?php

namespace AsyncAws\Iam\Exception;

use AsyncAws\Core\Exception\Http\ServerException;

/**
 * The request processing has failed because of an unknown error, exception or failure.
 */
final class ServiceFailureException extends ServerException
{
}
