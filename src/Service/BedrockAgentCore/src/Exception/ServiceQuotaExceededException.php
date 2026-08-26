<?php

namespace AsyncAws\BedrockAgentCore\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The exception that occurs when the request would cause a service quota to be exceeded. Review your service quotas and
 * either reduce your request rate or request a quota increase.
 */
final class ServiceQuotaExceededException extends ClientException
{
}
