<?php

namespace AsyncAws\BedrockAgentCore\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The exception that occurs when the specified resource does not exist. This can happen when using an invalid
 * identifier or when trying to access a resource that has been deleted.
 */
final class ResourceNotFoundException extends ClientException
{
}
