<?php

namespace AsyncAws\BedrockAgentCore\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The exception that occurs when there is a retryable conflict performing an operation. This is a temporary condition
 * that may resolve itself with retries. We recommend implementing exponential backoff retry logic in your application.
 */
final class RetryableConflictException extends ClientException
{
}
