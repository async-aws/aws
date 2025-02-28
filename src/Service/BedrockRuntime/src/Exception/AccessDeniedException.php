<?php

namespace AsyncAws\BedrockRuntime\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request is denied because you do not have sufficient permissions to perform the requested action. For
 * troubleshooting this error, see AccessDeniedException [^1] in the Amazon Bedrock User Guide.
 *
 * [^1]: https://docs.aws.amazon.com/bedrock/latest/userguide/troubleshooting-api-error-codes.html#ts-access-denied
 */
final class AccessDeniedException extends ClientException
{
}
