<?php

namespace AsyncAws\BedrockAgentCore\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The exception that occurs when you do not have sufficient permissions to perform an action. Verify that your IAM
 * policy includes the necessary permissions for the operation you are trying to perform.
 */
final class AccessDeniedException extends ClientException
{
}
