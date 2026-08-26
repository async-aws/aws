<?php

namespace AsyncAws\BedrockAgentCore\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The exception that occurs when there is an error in the runtime client. This can happen due to network issues,
 * invalid configuration, or other client-side problems. Check the error message for specific details about the error.
 */
final class RuntimeClientErrorException extends ClientException
{
}
