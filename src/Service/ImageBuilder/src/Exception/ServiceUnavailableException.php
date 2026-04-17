<?php

namespace AsyncAws\ImageBuilder\Exception;

use AsyncAws\Core\Exception\Http\ServerException;

/**
 * The service is unable to process your request at this time.
 */
final class ServiceUnavailableException extends ServerException
{
}
