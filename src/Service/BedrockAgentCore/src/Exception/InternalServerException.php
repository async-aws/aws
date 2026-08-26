<?php

namespace AsyncAws\BedrockAgentCore\Exception;

use AsyncAws\Core\Exception\Http\ServerException;

/**
 * The exception that occurs when the service encounters an unexpected internal error. This is a temporary condition
 * that will resolve itself with retries. We recommend implementing exponential backoff retry logic in your application.
 */
final class InternalServerException extends ServerException
{
}
