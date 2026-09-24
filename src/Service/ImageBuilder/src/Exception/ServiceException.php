<?php

namespace AsyncAws\ImageBuilder\Exception;

use AsyncAws\Core\Exception\Http\ServerException;

/**
 * An internal server error occurred while Image Builder processed the request. Retrying the request may succeed.
 */
final class ServiceException extends ServerException
{
}
