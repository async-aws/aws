<?php

namespace AsyncAws\BedrockAgent\Exception;

use AsyncAws\Core\Exception\Http\ServerException;

/**
 * An internal server error occurred. Retry your request.
 */
final class InternalServerException extends ServerException
{
}
