<?php

namespace AsyncAws\LocationService\Exception;

use AsyncAws\Core\Exception\Http\ServerException;

/**
 * The request has failed to process because of an unknown server error, exception, or failure.
 */
final class InternalServerException extends ServerException
{
}
