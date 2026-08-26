<?php

namespace AsyncAws\BedrockAgentCore\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The exception that occurs when the request was denied due to request throttling. This happens when you exceed the
 * allowed request rate for an operation. Reduce the frequency of requests or implement exponential backoff retry logic
 * in your application.
 */
final class ThrottlingException extends ClientException
{
}
