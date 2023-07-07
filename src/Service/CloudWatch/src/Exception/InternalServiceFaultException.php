<?php

namespace AsyncAws\CloudWatch\Exception;

use AsyncAws\Core\Exception\Http\ServerException;

/**
 * Request processing has failed due to some unknown error, exception, or failure.
 */
final class InternalServiceFaultException extends ServerException
{
}
